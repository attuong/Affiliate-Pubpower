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
        $this->setDefaultTable(TABLE_REPORT_PUB);
    }

    public function Druid($sql) {
        $headers = [
            'Content-Type: application/json',
            "Authorization: " . AUTHORIZATION_DRUID
        ];
        //create a new cURL resource
        $ch = curl_init(LINK_DRUID_REPORT);

        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sql));
        //set the content type to application/json and header
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //execute the POST request
        $result = curl_exec($ch);
        //close cURL resource
        curl_close($ch);

        if (empty($result)) {
            return error("No response received");
        }

        return $result;
    }

    public function findOneDruid($sql) {
        $result = $this->Druid(($sql));
        if (!$result) {
            return FALSE;
        }

        $data = json_decode($result, TRUE);
        if (!$data) {
            return FALSE;
        }
        if (!empty($data['error'])) {
            return $data;
        }

        return $data[0];
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

    public function list_by_filters($filters) {
        $userLogin = UserModel::getInstance()->get_user_login();
        $pubs = UserModel::getInstance()->getPublishersByAffiliate($userLogin->id, ["id", "email", "status"]);
        $PubIDs = get_array_by_key_of_array($pubs, "id", 'object');
        $time = $this->get_time_month($filters['start_month'], $filters['end_month']);
        $query = ["user_id" => $userLogin->id];

        $data = [];
        if (!$time) {
            return $data;
        }

        foreach ($time as $month) {
            // $query['month'] = $month;
            // $reports = $this->setTable(TABLE_REPORT_PUB)->findOne($query);
            // if (!$reports) {
            //     continue;
            // }
            // $data[] = $reports;


            // $start_date = date("Y-m-d", strtotime($inputs['start_day']));
            // $end_date = date("Y-m-d", strtotime($inputs['end_day']));
            $sql = [
                "query"   => "SELECT SUM(netRevenue) as netRevenue FROM \"pw_tag\" WHERE FLOOR(__time TO DAY) like '" . $month . "%'  AND pubId IN (" . implode($PubIDs, ",") . ")",
                "context" => ["sqlTimeZone" => "America/New_York"]
            ];
            $reprot = $this->findOneDruid($sql);
            if (!empty($reprot['error'])) {
                return $reprot;
            }
            $data[] = (object)[
                "month"      => $month,
                "revenue"    => number_format($reprot["netRevenue"], 2),
                "commission" => number_format($reprot["netRevenue"] * 0.03, 2),
            ];
        }
        return $data;
    }

    //month
    public function get_time_month($start, $end) {
        $min_month = $start . '01';
        $max_month = $end . '01';

        $times = [];
        for ($i = 0; date('Ymd', strtotime("+" . $i . " MONTH ", strtotime($min_month))) <= $max_month; $i ++) {
            $times[] = date('Y-m', strtotime("+" . $i . " MONTH ", strtotime($min_month)));
        }
        return array_reverse($times);
    }

    public function get_report_website_by_month($month) {
        $domains = DomainModel::getInstance()->getAllDomainForAffiliate();
        if (!$domains) {
            return [];
        }

        $data = [];
        foreach ($domains as $domain) {
            $revenue = $this->get_revenue_for_domain_by_month($domain->id, $month);
            if (!empty($revenue['error'])) {
                return $revenue;
            }
            if (empty($revenue)) {
                continue;
            }
            $domain->revenue = number_format($revenue, 2);
            $domain->commission = number_format($revenue * 0.03, 2);
            $data[] = $domain;
        }
        if ( $data ){
            $data = _orderBy($data, "revenue");
        }
        return $data;
    }

    public function get_revenue_for_domain_by_month($domain_id, $month) {
        $sql = [
            "query"   => "SELECT SUM(netRevenue) as netRevenue FROM \"pw_tag\" WHERE FLOOR(__time TO DAY) like '" . $month . "%'  AND inventoryId IN (" . $domain_id . ")",
            "context" => ["sqlTimeZone" => "America/New_York"]
        ];
        $report = $this->findOneDruid($sql);
        if (!empty($report['error'])) {
            return $report;
        }
        return !empty($report["netRevenue"]) ? $report["netRevenue"] : 0;
    }

}
