<?php

require realpath(dirname(__FILE__)) . '/System.php';
require_once LIBRARY_PATH . '/aerospike/vendor/autoload.php';

use core\Model;

class dfpSync extends Model
{

    private $aero_reports = 'vli_reports';
    private $today;
    private $client;
    private $bidderAdx;
    private $bidderAdsense;
    private $amazonReport = false;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->setDefaultTable(TABLE_DFP_REPORT);
        $this->today = date('Ymd');
        $this->bidderAdx = strtolower($this->getBidderGoogle('adx'));
        $this->bidderAdsense = strtolower($this->getBidderGoogle('adsense'));
        // connect aerospike
        $config = ["hosts" => [["addr" => "127.0.0.1", "port" => 3000]], "shm" => []];
        $this->client = new Aerospike($config, true);
        if (!$this->client->isConnected()) {
            return;
        }
        $this->dfpSync();
        $this->clearData();
    }

    private function dfpSync()
    {

        $data = $this->getData();
        if (!$data) {
            return;
        }

        $reportData = json_decode($data->data);
        if (!$reportData || (!isset($reportData->rows) && !isset($reportData->amazon))) {
            $this->update(
                ['id' => $data->id],
                ['status' => 'json decode error']
            );
            return;
        }

        // handle report and update report
        if (isset($reportData->rows)) {
            $reports = $this->mergeReportByDomain($reportData->rows);
        } elseif (isset($reportData->amazon)) {
            $this->amazonReport = true;
            $reports = $this->mergeReportByDomain($reportData->amazon);
        }

        foreach ($reports as $val) {
            $this->updateReports($val);
        }

        $this->update(
            ['id' => $data->id],
            ['status' => 'done']
        );
        // $this->db->remove($this->table_dfp_reports, ['id' => $data['id']]);

    }

    private function getAeroSpikeData($key)
    {
        if ($this->client->exists($key, $foo) == Aerospike::OK) {
            $status = $this->client->get($key, $record);
            if ($status == Aerospike::OK) {
                return $record['bins'];
            }
        }
        return false;
    }

    private function updateReports($data)
    {
        if (count($data['report']) == 0) {
            return;
        }

        $key = $this->client->initKey('ads', $this->aero_reports, "{$this->today}_{$data['domainID']}");
        $aeData = $this->getAeroSpikeData($key);
        if (!$aeData) {
            return;
        }

        foreach ($data['report'] as $item) {
            foreach ($item as $tag) {

                // update adx/adsense report on tag ID for VLI
                $keyItem = 'z' . $tag['tag_id'];
                $AdxNewReportData = $this->buildTagReportForVLI($tag);
                if ($AdxNewReportData !== false) {

                    if($this->amazonReport == true){
                        $bidderCode = $this->getAmazonBidderCode();
                    }else{
                        $bidderCode = $tag['adsense_impr'] > $tag['adx_impr'] ? $this->bidderAdsense : $this->bidderAdx;
                    }

                    if (isset($aeData[$keyItem])) {
                        $reportItem = $aeData[$keyItem];
                        $reportItem[$bidderCode] = $AdxNewReportData;
                    } else {
                        $reportItem = [$bidderCode => $AdxNewReportData];
                    }

                    // update to ae
                    $status = $this->client->put($key, [$keyItem => $reportItem], (86400 * 5));

                }

                // update adx/adsense report on tag ID for Publisher
                $this->updateDfpReportForPublisher($tag);

            }
        }
    }

    private function buildTagReportForVLI($tagData)
    {
        $AdxNewReportData = [
            'impressions' => $tagData['adsense_impr'] > $tagData['adx_impr'] ? $tagData['adsense_impr'] : $tagData['adx_impr'],
            'revenue' => $tagData['adsense_revenue'] > $tagData['adx_revenue'] ? $tagData['adsense_revenue'] : $tagData['adx_revenue'],
            'mobile_imp' => 0,
            'mobile_rev' => 0,
        ];

        // return 15% for Adnimation
        if ($tagData['adx_revenue'] > $tagData['adsense_revenue']) {
            $AdxNewReportData['revenue'] *= 0.85;
        }

        if ($AdxNewReportData['impressions'] == 0) {
            return false;
        }

        return $AdxNewReportData;
    }

    private function updateDfpReportForPublisher($tagData)
    {
        if(!isset($tagData['pub_adsense_impr'])){
            return;
        }
        $reportData = [
            'user_adx_imp' => $tagData['pub_adsense_impr'] > $tagData['pub_adx_impr'] ? $tagData['pub_adsense_impr'] : $tagData['pub_adx_impr'],
            'user_adx_rev' => $tagData['pub_adsense_revenue'] > $tagData['pub_adx_revenue'] ? $tagData['pub_adsense_revenue'] : $tagData['pub_adx_revenue'],
        ];

        if ($reportData['user_adx_imp'] == 0) {
            return;
        }

        $this->updateDfpReportByUser($tagData['tag_id'], $reportData, 'admin');
        $this->updateDfpReportByUser($tagData['tag_id'], $reportData, 'pub');
    }

    private function updateDfpReportByUser($tagID, $update, $user)
    {
        $table = $user == 'admin' ? TABLE_REPORT_TAG : TABLE_REPORT_TAG_FOR_PUBLISHER;
        $find = $this->setTable($table)->findOne(
            ['tag_id' => $tagID, 'date' => $this->today],
            ['id']
        );

        if ($find) {
            $this->setTable($table)->update(
                ['id' => $find->id],
                $update
            );
        } else {
            $insert = $this->getReportInfoByTag($tagID);
            $this->setTable($table)->insert(
                array_merge($insert, $update)
            );
        }
    }

    private function getBidderGoogle($type)
    {
        $find = $this->setTable(TABLE_BIDDER)->findOne(
            ['google_type' => $type]
        );
        return $find ? $find->name : 'Adsense';
    }

    private function getAmazonBidderCode(){
        $find = $this->setTable(TABLE_BIDDER)->findOne(
            ['id' => 33]
        );
        return $find ? strtolower($find->name) : 'amazon';
    }

    private function getData()
    {
        return $this->findOne(
            ['status' => '', 'data' => ['!=' => ''], 'date' => $this->today],
            [],
            ['id' => 'DESC']
        );
    }

    private function findDomain($keyword)
    {
        return $this->setTable(TABLE_DOMAIN)->findOne(
            ['name' => ['$like' => "%{$keyword}%"]]
        );
    }

    private function mergeReportByDomain($reports)
    {
        $resultByDomain = [];
        $result = [];
        // get domain list by domain name in dfp report
        foreach ($reports as $item) {
            $domain = $this->findDomain(trim($item->domain));

            if (!$domain) {
                continue;
            }

            $resultByDomain[$domain->id][] = $item;
        }

        // merge dfp report with tag
        foreach ($resultByDomain as $domainID => $reportData) {
            // get tag from report bidder by Domain ID
            $tags = $this->getTagByDomain($domainID);
            if (!$tags) {
                continue;
            }

            $report = $this->mergeData($tags, $reportData);
            $result[] = [
                'domainID' => $domainID,
                'report' => $report,
            ];
        }
        return $result;
    }

    private function mergeData($tags, $reportData)
    {
        $reports = [];
        $ritem = [
            'adsense_impr' => 0, 'adsense_revenue' => 0, 'adx_impr' => 0, 'adx_revenue' => 0,
            'pub_adsense_impr' => 0, 'pub_adsense_revenue' => 0, 'pub_adx_impr' => 0, 'pub_adx_revenue' => 0,
        ];
        
        foreach ($reportData as $item) {

            if ($this->amazonReport == true) {
                $item->order = 'Amazon Display';
            }

            $item->order = trim($item->order);
            $dfpOrder = $this->getDfpOrder($item->order);
            if (!$dfpOrder) {
                continue;
            }

            if (!isset($reports[$dfpOrder->tag_type_used])) {
                $reports[$dfpOrder->tag_type_used] = $ritem;
            }

            // report in dfp order with adx/adsense link account owner by VLI
            if ($dfpOrder->link_account_owner == 'vli') {
                if (isset($item->adsense_impr)) {
                    $reports[$dfpOrder->tag_type_used]['adsense_impr'] += $item->adsense_impr;
                    $reports[$dfpOrder->tag_type_used]['adsense_revenue'] += $item->adsense_revenue;
                }
                if (isset($item->adx_impr)) {
                    $reports[$dfpOrder->tag_type_used]['adx_impr'] += $item->adx_impr;
                    $reports[$dfpOrder->tag_type_used]['adx_revenue'] += $item->adx_revenue;
                }
                if (isset($item->amz_impr)) {
                    $reports[$dfpOrder->tag_type_used]['adsense_impr'] += $item->amz_impr;
                    $reports[$dfpOrder->tag_type_used]['adsense_revenue'] += $item->amz_revenue;
                }
            }
            // report in dfp order with adx/adsense link account owner by Publisher
            if ($dfpOrder->link_account_owner == 'pub') {
                if($item->domain != 'grammar-monster.com'){ // fix tạm thời
                    $reports[$dfpOrder->tag_type_used]['pub_adsense_impr'] += $item->adsense_impr;
                    $reports[$dfpOrder->tag_type_used]['pub_adsense_revenue'] += $item->adsense_revenue;
                    $reports[$dfpOrder->tag_type_used]['pub_adx_impr'] += $item->adx_impr;
                    $reports[$dfpOrder->tag_type_used]['pub_adx_revenue'] += $item->adx_revenue;
                }
            }

        }

        foreach ($reports as $tagTypeID => $reportItem) {
            if (isset($tags[$tagTypeID])) {
                $tags[$tagTypeID] = $this->mergeDataItem($tags[$tagTypeID], $reportItem);
            }
        }

        return $tags;
    }

    private function mergeDataItem($tagType, $reportItem)
    {
        $totalRevenue = $tagType['total_revenue'];
        unset($tagType['total_revenue']);
        foreach ($tagType as $key => $tag) {
            $rate = round($tag['revenue'] / $totalRevenue, 2);
            $tag['adsense_impr'] = round($reportItem['adsense_impr'] * $rate);
            $tag['adsense_revenue'] = $reportItem['adsense_revenue'] * $rate;
            $tag['adx_impr'] = round($reportItem['adx_impr'] * $rate);
            $tag['adx_revenue'] = $reportItem['adx_revenue'] * $rate;

            $tag['pub_adsense_impr'] = round($reportItem['pub_adsense_impr'] * $rate);
            $tag['pub_adsense_revenue'] = $reportItem['pub_adsense_revenue'] * $rate;
            $tag['pub_adx_impr'] = round($reportItem['pub_adx_impr'] * $rate);
            $tag['pub_adx_revenue'] = $reportItem['pub_adx_revenue'] * $rate;

            $tagType[$key] = $tag;
        }
        return $tagType;
    }

    private function getDfpOrder($orderName)
    {
        return $this->setTable(TABLE_DFP_ORDER)->findOne(
            ['name' => $orderName]
        );
    }

    // get tag from report bidder by Domain ID
    private function getTagByDomain($domainID)
    {
        // get report from bidder report by DomainID
        $query = ['domain_id' => $domainID, 'date' => $this->today, 'bidder' => ['$nin' => [31, 32]]];
        $reports = $this->setTable(TABLE_REPORT_BIDDER)->find(
            $query,
            ['tag_id', 'tag_type', 'revenue']
        );
        if (!$reports) {
            return false;
        }

        // map report by tag ID
        $results = [];
        foreach ($reports as $item) {
            if ($this->getTagStatus($item->tag_id) == 'off') {
                continue;
            }
            if (!isset($results[$item->tag_id])) {
                $results[$item->tag_id] = [
                    'tag_id' => $item->tag_id,
                    'tag_type' => $item->tag_type,
                    'revenue' => 0,
                ];
            }
            $results[$item->tag_id]['revenue'] += $item->revenue;
        }

        // map report by tag type ID
        $return = [];
        foreach ($results as $item) {
            $tagItem = [
                'tag_id' => $item['tag_id'],
                'revenue' => $item['revenue'],
                'adsense_impr' => 0,
                'adsense_revenue' => 0,
                'adx_impr' => 0,
                'adx_revenue' => 0,
            ];

            $tagTypeID = $item['tag_type'];
            // map tag type is "smart banner" to type "display"
            if($item['tag_type'] == 6){
                $tagTypeID = 0;
            }
            $return[$tagTypeID][] = $tagItem;
            if (!isset($return[$tagTypeID]['total_revenue'])) {
                $return[$tagTypeID]['total_revenue'] = 0;
            }
            $return[$tagTypeID]['total_revenue'] += $item['revenue'];
        }

        return $return;
    }

    private function getTagStatus($tagID)
    {
        $tag = $this->setTable(TABLE_ADTAGS)->findOne(
            ['id' => $tagID],
            ['status']
        );

        return !$tag ? 'off' : $tag->status;
    }

    private function getReportInfoByTag($tagID)
    {
        $tag = $this->setTable(TABLE_ADTAGS)->findOne(
            ['id' => $tagID],
            ['domain_id', 'type_id']
        );
        if (!$tag) {
            return false;
        }

        $domain = $this->setTable(TABLE_DOMAIN)->findOne(
            ['id' => $tag->domain_id],
            ['user_id']
        );
        $user = $this->setTable(TABLE_USER)->findOne(
            ['id' => $domain->user_id],
            ['presenter']
        );

        return [
            'presenter' => $user->presenter,
            'user_id' => $domain->user_id,
            'domain_id' => $tag->domain_id,
            'tag_id' => $tagID,
            'tag_type' => $tag->type_id,
            'date' => $this->today,
        ];
    }

    private function clearData(){
        $todayTime = strtotime(date("Ymd"));
        $clearDate = date('Ymd', ($todayTime - (86400 * 2)));
        $this->remove(
            [
                'date' => ['<=' => $clearDate]
            ]
        );
    }

}

new dfpSync;
