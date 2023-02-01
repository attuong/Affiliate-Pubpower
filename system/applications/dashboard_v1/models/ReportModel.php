<?php

use core\Model;

/**
 * Description of ReportModel 
 * 
 * @author Tuan 
 */
class ReportModel extends Model {

    public function __construct($config = []) {
        parent::__construct($config);
        $this->setDefaultTable(TABLE_REPORT_TAG);
    }

    public function handle_input_for_filter($inputs) {
        if (empty($inputs['start_month']) || empty($inputs['end_month'])) {
            $inputs['start_month'] = date('Ym', strtotime('-6 month'));
            $inputs['end_month'] = date('Ym', strtotime('this month'));
        } else {
            $start_month = explode('/', $inputs['start_month']);
            $inputs['start_month'] = $start_month[1] . $start_month[0];
            $end_month = explode('/', $inputs['end_month']);
            $inputs['end_month'] = $end_month[1] . $end_month[0];
        }

        return $inputs;
    }

    public function handle_input_for_toplist($inputs) {
        if (empty($inputs['start_day']) || empty($inputs['end_day'])) {
            $inputs['start_day'] = date('Ymd', strtotime('first day of this month'));
            $inputs['end_day'] = date('Ymd', strtotime('today'));
        }
        return $inputs;
    }

    public function filters($filters) {
        $user_login = UserModel::getInstance()->get_user_login();
        $query['user_id'] = $user_login->id;

        return $query;
    }

    public function list_by_filters($filters) {

        $time = $this->get_time_month($filters['start_month'], $filters['end_month']);
        $query = $this->filters($filters);

        $data = [];
        if (!$time) {
            return $data;
        }

        foreach ($time as $month) {
            $query['month'] = $month;
            $reports = $this->setTable(TABLE_REPORT_AFFILIATE)->findOne($query);
            if (!$reports) {
                continue;
            }
            $data[] = $reports;
        }
        return $data;
    }

    //date
    public function get_time_date($start, $end) {
        if ((int) $start < 20181226) {
            $start = 20181226;
        }
        $times = intervals($start, $end, 'Ymd');
        $times = array_reverse($times);
        $today = date('Ymd', strtotime('today'));
        if ($times) {
            foreach ($times as $key => $time) {
                if ($time > $today) {
                    unset($times[$key]);
                }
            }
        }

        return $times;
    }

    //month
    public function get_time_month($start, $end) {
        $min_month = $start . '01';
        $max_month = $end . '01';

        $times = [];
        for ($i = 0; date('Ymd', strtotime("+" . $i . " MONTH ", strtotime($min_month))) <= $max_month; $i++) {
            $times[] = date('Ym', strtotime("+" . $i . " MONTH ", strtotime($min_month)));
        }
        return array_reverse($times);
    }

    public function get_by_date($query, $date) {
        $query['date'] = $date;
        $field = [
            'SUM(revenue) as revenue',
            'SUM(paid) as paid',
            'SUM(passback) as passback',
        ];

        //get report 
        $reports = $this->setTable(TABLE_REPORT_TAG_FOR_PUBLISHER)->findOne($query, $field);
        if ($reports) {
            $reports->date = $date;
        }
        return $reports;
    }

    public function get_report_website_by_month($month) {
        $domains = DomainModel::getInstance()->getAllDomainForAffiliate();
        if (!$domains) {
            return [];
        }

        $data = [];
        foreach ($domains as $domain) {
            $revenue = $this->get_revenue_for_domain_by_month($domain->id, $month);
            if (empty($revenue->revenue)) {
                continue;
            }
            $domain->revenue = $revenue->revenue;
            $data[] = $domain;
        }
        return $data;
    }

    public function get_revenue_for_domain_by_month($domain_id, $month) {
        $user_login = UserModel::getInstance()->get_user_login();
        $query = [
            'domain_id' => $domain_id,
            'date' => ['$like' => "{$month}%"],
            'aff_id' => $user_login->id
        ];
        $field = ['SUM(revenue) as revenue'];
        $report = $this->setTable(TABLE_REPORT_TAG_FOR_PUBLISHER)->findOne($query, $field);
        return $report;
    }

//
//    public function get_report_publisher_by_month($month) {
//        $publishers = UserModel::getInstance()->getAllPublisherForAffiliate();
//        if (!$publishers) {
//            return [];
//        }
//
//        $data = [];
//        foreach ($publishers as $user) {
//            $revenue = $this->get_revenue_for_publisher_by_month($user->id, $month);
//            $user->revenue = $revenue->revenue;
//            $data[] = $user;
//        }
//        return $data;
//    }
//
//    public function get_revenue_for_publisher_by_month($user_id, $month) {
//        $user_login = UserModel::getInstance()->get_user_login();
//        $query = [
//            'user_id' => $user_id,
//            'date' => ['$like' => "{$month}%"],
//            'presenter' => $user_login->id
//        ];
//        $field = ['SUM(revenue) as revenue'];
//        $report = $this->setTable(TABLE_REPORT_TAG_FOR_PUBLISHER)->findOne($query, $field);
//        return $report;
//    }
}
