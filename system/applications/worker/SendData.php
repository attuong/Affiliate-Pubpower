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

        $result = [];
        foreach ($approvedDomains as $key => $domain) {
            $data = $this->getAeroSpikeData($domain->id);
            if ($data) {
                $result[] = [
                    'key' => "{$this->today}_{$domain->id}",
                    'data' => $data
                ];
            }
        }

        $status = sendData(
            json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'report'
        );

        print_r($status);
        die;
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

}

new Sync;
