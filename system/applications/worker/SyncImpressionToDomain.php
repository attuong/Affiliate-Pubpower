<?php

require realpath(dirname(__FILE__)) . '/System.php';

use core\Model;

class SyncImpression extends Model {

    public function __construct($config = []) {
        parent::__construct($config);
        $this->sync();
    }

    public function sync() {

        $todayTime = strtotime(date("Ymd"));
        $yesterday = date('Ymd', $todayTime - 86400 - 7200);
        $domains = $this->setTable(TABLE_DOMAIN)->find(
                ['status' => 'approved'], ['id']
        );

        foreach ($domains as $item) {
            $report = $this->setTable(TABLE_REPORT_TAG)->findOne(
                    ['domain_id' => $item->id, 'date' => $yesterday], 
                    ['sum(paid) as paid', 'sum(passback) as passback', 'sum(user_adx_imp) as user_adx_imp', 'sum(revenue) as revenue']
            );
            if ($report) {
                $this->setTable(TABLE_DOMAIN)->update(
                        ['id' => $item->id], 
                        [
                            'impressions' => ($report->paid + $report->passback + $report->user_adx_imp),
                            'revenue' =>  $report->revenue   
                        ]
                );
            }
        }
    }

}

new SyncImpression;
