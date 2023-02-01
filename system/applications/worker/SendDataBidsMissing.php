<?php

require realpath(dirname(__FILE__)) . '/System.php';
require_once LIBRARY_PATH . '/aerospike/vendor/autoload.php';

use core\Model;

class BidsMissingSync extends Model
{

    private $aero_log = 'vli_logs';
    private $client;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->setDefaultTable(TABLE_BIDS_MISSING);
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
        $GLOBALS['result'] = [];
        $key = $this->client->scan("ads", $this->aero_log, function ($record) {
            $bin = $record["bins"];
            $domainID = isset($bin["d"]) ? $bin["d"] : false;
            if (!$domainID) {
                return;
            }
            $GLOBALS['result'][] = [
                'key' => $domainID,
                'data' => $bin
            ];
        });

        $status = sendData(
            json_encode($GLOBALS['result'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'bids_missing'
        );
    }

}

new BidsMissingSync;
