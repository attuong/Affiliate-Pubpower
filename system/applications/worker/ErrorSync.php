<?php

require realpath(dirname(__FILE__)) . '/System.php';
require_once LIBRARY_PATH . '/aerospike/vendor/autoload.php';

use core\Model;

class ErrorSync extends Model {

    private $aero_error_log = 'error_log';
    private $client;
    private $today;
    private $aeDataStructs = [
        'Today (date("Ymd")' => [
            "domainID" => [
                "bidder + error_code + tag_type + device + creativeID (string)" => [
                    "data" => [
                        "domain" => "domainID",
                        "error_code" => "vast error code",
                        "bidder" => "bidder Code",
                        "tag_type" => "instream, outstream or videodiscover",
                        "device" => "pc or mobile",
                        "creativeID" => "ad creative ID",
                    ],
                    "count" => "(int)"
                ]
            ]
        ]
    ];

    public function __construct($config = []) {
        parent::__construct($config);
        $this->setDefaultTable(TABLE_ERROR_LOG);
        $this->today = date('Ymd');
        // connect aerospike
        $config = ["hosts" => [["addr" => "127.0.0.1", "port" => 3000]], "shm" => []];
        $this->client = new Aerospike($config, true);
        if (!$this->client->isConnected()) {
            return;
        }

        $clearTime = strtotime($this->today) - (86400 * 10);
        $this->remove(
                ['date' => ['<=' => date("Ymd", $clearTime)]]
        );

        $this->sync();
    }

    public function sync() {

        $key = $this->client->initKey("ads", $this->aero_error_log, $this->today);
        $data = false;
        $tagType = ['instream' => 1, 'outstream' => 2, 'videodiscover' => 4, 'outstream_inline' => 5];

        if ($this->client->exists($key, $foo) == Aerospike::OK) {
            $status = $this->client->get($key, $record);
            if ($status == Aerospike::OK) {
                $data = $record['bins'];
            }
        }

        if (!$data) {
            return;
        }

        foreach ($data as $value) {
            foreach ($value as $vl) {

                $err = $vl['data'];
                $domainID = (int) $err['domain'];
                if ($domainID == 0) {
                    continue;
                }

                $domain = $this->getDomainByID($domainID);
                $tag_type_id = isset($tagType[$err['tag_type']]) ? $tagType[$err['tag_type']] : 0;
                if (!$domain) {
                    continue;
                }

                $query = [
                    'domain' => $domainID,
                    'bidder' => $err['bidder'],
                    'error_code' => $err['error_code'],
                    'tag_type' => $tag_type_id,
                    'device' => $err['device'],
                    'creativeID' => $err['creativeID'],
                ];

                $find = $this->findOne(
                        $query, ['id']
                );

                if ($find) {
                    $this->update(
                            ['id' => $find->id], ['count' => $vl['count']]
                    );
                } else {
                    $query['count'] = $vl['count'];
                    $query['date'] = $this->today;
                    $this->insert($query);
                }
            }
        }
    }

    private function getDomainByID($domainID) {
        return $this->setTable(TABLE_DOMAIN)->findOne(
                        ['id' => $domainID, 'deleted_at' => 0], ['id']
        );
    }

}

new ErrorSync;
