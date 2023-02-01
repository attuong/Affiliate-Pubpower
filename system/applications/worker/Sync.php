<?php

require realpath(dirname(__FILE__)) . '/System.php';
require_once LIBRARY_PATH . '/aerospike/vendor/autoload.php';

$reportStructs = [
    'Today_DomainID' => [
        'z{ZoneID}' => [
            'BidderCode' => [
                'impressions' => 'total paid impressions',
                'revenue' => 'total revenue',
                'mobile_imp' => 'total paid impressions on mobile',
                'mobile_rev' => 'total revenue on mobile',
            ],
            'passback' => [
                'impressions' => 'total passback impressions',
                'mobile_imp' => 'total passback impressions on mobile',
            ],
        ],
    ],
];

use core\Model;

class Sync extends Model
{

    private $aero_reports = 'vli_reports';
    private $aero_domains = 'domains';
    private $client;
    private $today;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->today = date('Ymd');

        // connect aerospike
        $config = ["hosts" => [["addr" => "127.0.0.1", "port" => 3000]], "shm" => []];
        $this->client = new Aerospike($config, true);
        if (!$this->client->isConnected()) {
            return;
        }

        $this->sync();
    }

    public function sync()
    {
        $approvedDomains = $this->getApprovedDomain();

        if (!$approvedDomains) {
            return;
        }

        foreach ($approvedDomains as $key => $domain) {

            $data = $this->getAeroSpikeData($domain->id);
            if ($data) {
                $this->updateReports($data, $domain);
            }

            if($key % 50 == 0){
                sleep(3);
            }
        }
    }

    private function getAeroSpikeData($domainID)
    {
        $ky = "{$this->today}_{$domainID}";
        $key = $this->client->initKey('ads', $this->aero_reports, $ky);
        if ($this->client->exists($key, $foo) == Aerospike::OK) {
            $status = $this->client->get($key, $record);
            if ($status == Aerospike::OK) {
                return $record['bins'];
            }
        }
        return false;
    }

    private function getApprovedDomain(){
        $query = ['status' => 'approved'];
        $field = ['id', 'user_id', 'status', 'ad_fill', 'fill_rate_adjusted'];
        $order = ['impressions' => 'DESC'];
        return $this->setTable(TABLE_DOMAIN)->find($query, $field, $order);
    }

    private function updateReports($data, $domain)
    {

        $presenterID = $this->getPresenterID($domain->user_id);
        $revenue_rate = $this->getUserRateSharing($domain->id, 0);
        $impression_rate = $this->getUserRateSharing($domain->id, 1);
        $adxEcpm_display = $this->getAdxEcpmByDomain($domain->id, 'display');
        $adxEcpm_video = $this->getAdxEcpmByDomain($domain->id, 'video');

        foreach ($data as $key => $item) {
            $tagID = str_replace('z', '', $key); // sai
            $tagTypeID = $this->getTagTypeID($tagID, $domain->id);

            if ($tagTypeID === false) {
                continue;
            }

            $define_data = [
                'domain_id' => $domain->id,
                'presenter' => $presenterID,
                'user_id' => $domain->user_id,
                'tag_id' => $tagID,
                'tag_type' => $tagTypeID,
                'date' => $this->today,
            ];

            // update bidder paid reports ///////////////////////
            foreach ($item as $bidder => $report) {

                if ($bidder == "passback") {
                    continue;
                }

                if ($domain->ad_fill == 0 && $bidder == "adx") {
                    continue;
                }

                $bidderID = $bidder == 'adx' ? '0' : $this->getBidderID($bidder);
                if ($bidderID === false) {
                    continue;
                }

                $report = $this->typeReport($report);
                // handle Adx report if domain set ad_fill is 1 (fill 100%)
                if ($bidderID == 0 && $domain->ad_fill == 1) {
                    if ($tagTypeID == 0) {
                        $report['revenue'] = ($report['impressions'] / 1000) * $adxEcpm_display;
                        $report['mobile_rev'] = ($report['mobile_imp'] / 1000) * $adxEcpm_display;
                    }
                    if (in_array($tagTypeID, [1, 4])) {
                        $report['revenue'] = ($report['impressions'] / 1000) * $adxEcpm_video;
                        $report['mobile_rev'] = ($report['mobile_imp'] / 1000) * $adxEcpm_video;
                    }
                }

                // update paid report by bidder for admin
                $this->updateReportBidder($define_data, $report, $bidderID, 'admin');
                // update paid report by bidder for user
                //convert by sharing rate
                $report['impressions'] = (int) round($report['impressions'] * $impression_rate);
                $report['revenue'] *= $revenue_rate;
                $report['mobile_imp'] = (int) round($report['mobile_imp'] * $impression_rate);
                $report['mobile_rev'] *= $revenue_rate;

                $this->updateReportBidder($define_data, $report, $bidderID, 'user');
                ///////////
            }

            // update tag report for admin
            $adminTagReport = $this->getPaidTagReport($tagID, 'admin');
            if (isset($item["passback"])) {
                $adminTagReport->passback = $item["passback"]['impressions'];
                if($domain->fill_rate_adjusted > 0){
                    $pbRate = $domain->fill_rate_adjusted / 100;
                    $adminTagReport->passback = round($adminTagReport->passback * $pbRate);
                }
            }
            $this->updateReportTag($define_data, $adminTagReport, 'admin');
            // update paid tag report for user
            $pubTagReport = $this->getPaidTagReport($tagID, 'pub');
            if (isset($item["passback"])) {
                $pubTagReport->passback = $item["passback"]['impressions'];
                if($domain->fill_rate_adjusted > 0){
                    $pbRate = $domain->fill_rate_adjusted / 100;
                    $pubTagReport->passback = round($pubTagReport->passback * $pbRate);
                }
            }
            $this->updateReportTag($define_data, $pubTagReport, 'pub');

        }
    }

    private function typeReport($report)
    {
        (int) $report['impressions'];
        (float) $report['revenue'];
        (int) $report['mobile_imp'];
        (float) $report['mobile_rev'];
        return $report;
    }

    private function updateReportBidder($define_data, $report, $bidderID, $user)
    {
        $table = $user == 'admin' ? TABLE_REPORT_BIDDER : TABLE_REPORT_BIDDER_FOR_PUBLISHER;
        $report_data = [
            'impressions' => $report['impressions'],
            'revenue' => $report['revenue'],
            'mobile_imp' => $report['mobile_imp'],
            'mobile_rev' => $report['mobile_rev'],
        ];
        $query = [
            'domain_id' => $define_data['domain_id'],
            'date' => $this->today,
            'tag_id' => $define_data['tag_id'],
            'bidder' => $bidderID,
        ];

        $find = $this->setTable($table)->findOne($query, ['id']);
        if ($find) {
            $this->setTable($table)->update(
                ['id' => $find->id],
                $report_data
            );
        } else {
            $report_data['bidder'] = $bidderID;
            $this->setTable($table)->insert(
                array_merge($define_data, $report_data)
            );
        }
    }

    private function updateReportTag($define_data, $report, $user)
    {
        $table = $user == 'admin' ? TABLE_REPORT_TAG : TABLE_REPORT_TAG_FOR_PUBLISHER;
        $report_data = [
            'paid' => $report->impressions,
            'revenue' => isset($report->revenue) ? $report->revenue : 0,
            'mobile_imp' => isset($report->mobile_imp) ? $report->mobile_imp : 0,
            'mobile_rev' => isset($report->mobile_rev) ? $report->mobile_rev : 0,
            'passback' => isset($report->passback) ? $report->passback : 0,
        ];
        $query = [
            'domain_id' => $define_data['domain_id'],
            'date' => $this->today,
            'tag_id' => $define_data['tag_id'],
        ];
        $find = $this->setTable($table)->findOne($query, ['id']);
        if ($find) {
            $this->setTable($table)->update(
                ['id' => $find->id],
                $report_data
            );
        } else {
            $this->setTable($table)->insert(
                array_merge($define_data, $report_data)
            );
        }
    }

    private function getPaidTagReport($tagID, $user){
        $table = $user == 'admin' ? TABLE_REPORT_BIDDER : TABLE_REPORT_BIDDER_FOR_PUBLISHER;
        return $this->setTable($table)->findOne(
            ['tag_id' => $tagID, 'date' => $this->today],
            [
                'sum(impressions) as impressions',
                'sum(revenue) as revenue',
                'sum(mobile_imp) as mobile_imp',
                'sum(mobile_rev) as mobile_rev'
            ]
        );
    }

    private function getBidderID($bidderCode)
    {
        $find = $this->setTable(TABLE_BIDDER)->findOne(['name' => $bidderCode], ['id']);
        return $find ? $find->id : false;
    }

    private function getDomainByID($domainID)
    {
        $query = ['id' => $domainID, 'deleted_at' => 0];
        $field = ['id', 'user_id', 'status', 'ad_fill', 'fill_rate_adjusted'];
        return $this->setTable(TABLE_DOMAIN)->findOne($query, $field);
    }

    private function getPresenterID($userID)
    {
        $find = $this->setTable(TABLE_USER)->findOne(['id' => $userID], ['presenter']);
        return $find ? $find->presenter : 0;
    }

    // $type: 0 - revenue rate, 1 - impression rate
    private function getUserRateSharing($domainID, $type)
    {
        $domain = $this->getDomainByID($domainID);
        if (!$domain) {
            return false;
        }

        $query = ['user_id' => $domain->user_id, 'type' => $type, 'create_time' => ['<=' => $this->today]];
        $field = ['rate'];
        $order = ['create_time' => 'DESC'];
        $rate = $this->setTable(TABLE_RATESHARING)->findOne($query, $field, $order);
        return $rate ? (float) ($rate->rate / 100) : 1;
    }

    private function getRateByBidder($bidderID, $date)
    {
        $query = ['bidder_id' => $bidderID, 'create_time' => ['<=' => $date]];
        $field = ['rate'];
        $order = ['create_time' => 'DESC'];
        $rate = $this->setTable(TABLE_RATEBIDDER)->findOne($query, $field, $order);
        return $rate ? (float) ($rate->rate / 100) : 1;
    }

    private function getAdxEcpmByDomain($domainID, $type)
    {
        $query = ['domain_id' => $domainID, 'type' => $type, 'create_time' => ['<=' => $this->today]];
        $field = ['rate'];
        $order = ['create_time' => 'DESC'];
        $rate = $this->setTable(TABLE_RATEBIDDER)->findOne($query, $field, $order);
        return $rate ? (float) $rate->rate : 0;
    }

    private function getTagTypeID($tagID, $domainID)
    {
        global $adTagType;

        $findTag = $this->setTable(TABLE_ADTAGS)->findOne(['id' => $tagID, 'deleted_at' => 0], ['type_id', 'domain_id']);
        if (!$findTag || $findTag->domain_id != $domainID) {
            return false;
        }

        return $findTag->type_id;
    }

}

new Sync;
