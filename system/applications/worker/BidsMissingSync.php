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
        $key = $this->client->scan("ads", $this->aero_log, function ($record) {
            $bin = $record["bins"];
            $domainID = isset($bin["d"]) ? $bin["d"] : false;
            if (!$domainID) {
                return;
            }

            foreach ($bin as $key => $item) {
                $size = explode("_", $key);
                if (count($size) < 2) {
                    continue;
                }

                $sizeID = $this->getSizeID($size[0], $size[1]);
                if (!$sizeID) {
                    continue;
                }

                $totalBidderSetting = $this->getTotalBidderSetting($domainID, $sizeID);
                if ($totalBidderSetting >= $item) {
                    continue;
                }

                $find = $this->findOne(
                    ["domain_id" => $domainID, "size_id" => $sizeID]
                );
                if ($find) {
                    $this->update(
                        ["id" => $find->id],
                        ["missing" => ($item - $totalBidderSetting)]
                    );
                } else {
                    $this->insert(
                        ["domain_id" => $domainID, "size_id" => $sizeID, "missing" => ($item - $totalBidderSetting)]
                    );
                }
            }
        });
    }

    private function getTotalBidderSetting($domainID, $sizeID)
    {
        return $this->setTable(TABLE_BIDDER_SETTINGS)->count(
            ["domain_id" => $domainID, "size_id" => $sizeID]
        );
    }

    private function getSizeID($width, $height)
    {
        $find = $this->setTable(TABLE_ADTAG_SIZES)->findOne(
            ["width" => $width, "height" => $height]
        );
        return $find ? $find->id : false;
    }

}

new BidsMissingSync;
