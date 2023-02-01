<?php

use core\Model;

define('LOGTYPE', [
    'USER_LOGIN' => 'USER_LOGIN',
    'USER_CREATE' => 'USER_CREATE',
    'USER_UPDATE' => 'USER_UPDATE',
    'SETTING_UPDATE' => 'SETTING_UPDATE',
    'BIDDER_CREATE' => 'BIDDER_CREATE',
    'BIDDER_UPDATE' => 'BIDDER_UPDATE',
    'BIDDER_CHANGE_STATUS' => 'BIDDER_CHANGE_STATUS',
    'DOMAIN_BIDDER_CREATE' => 'DOMAIN_BIDDER_CREATE',
    'DOMAIN_BIDDER_UPDATE' => 'DOMAIN_BIDDER_UPDATE',
    'DOMAIN_BIDDER_REMOVE' => 'DOMAIN_BIDDER_REMOVE',
    'DOMAIN_BIDDER_CREATE_ALL' => 'DOMAIN_BIDDER_CREATE_ALL',
    'DOMAIN_BIDDER_UPDATES' => 'DOMAIN_BIDDER_UPDATES',
    'DOMAIN_BIDDER_CREATES' => 'DOMAIN_BIDDER_CREATES',
    'DOMAIN_BIDDER_CHECK' => 'DOMAIN_BIDDER_CHECK',
    'BIDDER_SETTING_ITEM_CREATE' => 'BIDDER_SETTING_ITEM_CREATE',
    'BIDDER_SETTING_ITEM_UPDATE' => 'BIDDER_SETTING_ITEM_UPDATE',
    'BIDDER_SETTING_ITEM_REMOVE' => 'BIDDER_SETTING_ITEM_REMOVE',
    'BIDDER_SETTING_ITEM_UPDATE_API' => 'BIDDER_SETTING_ITEM_UPDATE_API',
    'BIDDER_SETTING_ITEM_CREATE_API' => 'BIDDER_SETTING_ITEM_CREATE_API',
    'BIDDER_GROUP_CREATE' => 'BIDDER_GROUP_CREATE',
    'BIDDER_GROUP_UPDATE' => 'BIDDER_GROUP_UPDATE',
    'BIDDER_GROUP_REMOVE' => 'BIDDER_GROUP_REMOVE',
    'BIDDER_GROUP_CREATE_FOR_SMART_BANNER' => 'BIDDER_GROUP_CREATE_FOR_SMART_BANNER',
    'BIDDER_GROUP_CLONE' => 'BIDDER_GROUP_CLONE',
    'BIDDER_GLOBAL_OFF_CREATE' => 'BIDDER_GLOBAL_OFF_CREATE',
    'BIDDER_GLOBAL_OFF_UPDATE' => 'BIDDER_GLOBAL_OFF_UPDATE',
    'BIDDER_GLOBAL_OFF_REMOVE' => 'BIDDER_GLOBAL_OFF_REMOVE',
    'BIDDER_UNCHANGED_ITEM_CREATE' => 'BIDDER_UNCHANGED_ITEM_CREATE',
    'BIDDER_UNCHANGED_ITEM_UPDATE' => 'BIDDER_UNCHANGED_ITEM_UPDATE',
    'BIDDER_UNCHANGED_ITEM_REMOVE' => 'BIDDER_UNCHANGED_ITEM_REMOVE',
    'BIDDER_UNCHANGED_ITEM_UPDATE_API' => 'BIDDER_UNCHANGED_ITEM_UPDATE_API',
    'BIDDER_UNCHANGED_ITEM_CREATE_API' => 'BIDDER_UNCHANGED_ITEM_CREATE_API',
    'BIDDER_UNCHANGED_ITEM_CHANGE_STATUS' => 'BIDDER_UNCHANGED_ITEM_CHANGE_STATUS',
    'ADTAG_CREATE' => 'ADTAG_CREATE',
    'ADTAG_UPDATE' => 'ADTAG_UPDATE',
    'ADTAG_REMOVE' => 'ADTAG_REMOVE',
    'ADTAG_WIDGET_ADD' => 'ADTAG_WIDGET_ADD',
    'ADTAG_WIDGET_REMOVE' => 'ADTAG_WIDGET_REMOVE',
    'ADTAG_CREAETE_WHEN_APPROVED_DOMAIN' => 'ADTAG_CREAETE_WHEN_APPROVED_DOMAIN',
    'ADSENSE_REMOVE' => 'ADSENSE_REMOVE',
    'ADSENSE_CREATE' => 'ADSENSE_CREATE',
    'SETTING_PASSBACK_CREATE' => 'SETTING_PASSBACK_CREATE',
    'SETTING_PASSBACK_UPDATE' => 'SETTING_PASSBACK_UPDATE',
    'SETTING_PASSBACK_REMOVE' => 'SETTING_PASSBACK_REMOVE',
    'GEOIP_CREATE' => 'GEOIP_CREATE',
    'GEOIP_UPDATE' => 'GEOIP_UPDATE',
    'DOMAIN_CREATE' => 'DOMAIN_CREATE',
    'DOMAIN_UPDATE' => 'DOMAIN_UPDATE',
    'DOMAIN_REJECT' => 'DOMAIN_REJECT',
    'RATELOG_UPDATE' => 'RATELOG_UPDATE',
    'PAYMENT_CREATE' => 'PAYMENT_CREATE',
    'PAYMENT_UPDATE' => 'PAYMENT_UPDATE',
    'PAYMENT_SCAN' => 'PAYMENT_SCAN',
    'PAYMENT_PAID' => 'PAYMENT_PAID',
    'PAYMENT_REQUEST_CREATE' => 'PAYMENT_REQUEST_CREATE',
    'PAYMENT_REQUEST_UPDATE' => 'PAYMENT_REQUEST_UPDATE',
    'REPORT_BY_HAND' => 'REPORT_BY_HAND',
]);

/**
 * Description of ActivityModel
 *
 * @author PC
 */
class ActivityModel extends Model {

    public $user;
    public $user_id;
    public $object_id;
    public $object_type;
    public $log_type;
    public $log_condition;
    public $log_change;
    public $log_last_data;

    public function __construct($config = array(), $prefix = false) {
        parent::__construct($config, $prefix);
        $this->setDefaultTable(TABLE_ACTIVITY);
    }

    public function Bidder_create($bidder_id, $change_log) {
        $this->setObject_id($bidder_id);
        return $this->Log_bidder(LOGTYPE['BIDDER_CREATE'], $change_log);
    }

    public function Bidder_update($bidder_id, $last_data, $change_log) {
        // ObjectID
        $this->setObject_id($bidder_id);

        // Condition
        $log_condition = json_encode(['id' => $bidder_id]);
        $this->setLog_condition($log_condition);

        // Last data
        $log_last_data = json_encode($last_data);
        $this->setLog_last_data($log_last_data);

        // Log
        $log = $this->Log_bidder(LOGTYPE['BIDDER_UPDATE'], $change_log);
        return $log;
    }

    public function Log_bidder($log_type, $change_log, $Object_type = TABLE_BIDDER) {

        // Change log
        $log_change = json_encode($change_log);
        $this->setLog_change($log_change);

        // Log type
        $this->setLog_type($log_type);

        // Set object type
        $this->setObject_type($Object_type);

        // Define $title
        $user = $this->getUser();
        $title = "";
        switch ($log_type) {
            case LOGTYPE['BIDDER_CREATE']:
                $title = "<b>{$user->email}</b> add new bidder";
                break;

            case LOGTYPE['BIDDER_UPDATE']:
                $title = "<b>{$user->email}</b> update bidder";
                break;

            case LOGTYPE['BIDDER_CHANGE_STATUS']:
                $title = "<b>{$user->email}</b> update status bidder setting item, bidder unchanged item when change status bidder";
                break;
        }

        // Save
        $log = $this->Save($title);
        return $log;
    }

    /**
     * Log if user update setting system
     * @param int $setting_id
     * @param object|array $last_data
     * @param array $change_log
     * @return boolean|array
     */
    public function Setting_update($setting_id, $last_data, $change_log) {
        // Condition
        $log_condition = json_encode(['id' => $setting_id]);
        $this->setLog_condition($log_condition);

        // Last data
        $log_last_data = json_encode($last_data);
        $this->setLog_last_data($log_last_data);

        // Change log
        $log_change = json_encode($change_log);
        $this->setLog_change($log_change);

        // Object
        $this->setObject($setting_id, TABLE_SETTINGS);

        // Log type
        $this->setLog_type(LOGTYPE['SETTING_UPDATE']);

        // Save Log
        $title = "<b>{$this->getUser()->email}</b> update setting";
        return $this->Save($title);
    }

    /**
     * Log info user login history
     * @return boolean|array
     */
    public function User_login() {
        $user_id = $this->getUser_id();
        $user = $this->getUser();
        $this->setObject_id($user_id);

        $data = [
            'ip' => getClientIp(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        ];
        $log_change = json_encode($data);
        return $this->Log_user(LOGTYPE['USER_LOGIN'], $log_change, $user);
    }

    /**
     * Log update user
     * @param array $change_log
     * @return boolean|array
     */
    public function User_create($change_log, $user_id) {
        $this->setObject_id($user_id);

        $log_data = json_encode($change_log);
        return $this->Log_user(LOGTYPE['USER_CREATE'], $log_data);
    }

    /**
     * Log create user
     * @param array $condition
     * @param array|object $last_data
     * @param array $change_log
     * @return boolean|array
     */
    public function User_update($condition, $last_data, $change_log) {
        $user_id = $this->getUser_id();
        $user = $this->getUser();
        $this->setObject_id($user_id);

        // Condition
        $log_condition = json_encode($condition);
        $this->setLog_condition($log_condition);

        // Last data
        $log_last_data = json_encode($last_data);
        $this->setLog_last_data($log_last_data);

        // Change log
        $log_change = json_encode($change_log);
        return $this->Log_user(LOGTYPE['USER_UPDATE'], $log_change, $user);
    }

    /**
     * Log all action of user
     * @param string $log_type
     * @param array $log_change
     * @return boolean|array
     */
    private function Log_user($log_type, $log_change, $user = []) {
        // Define $title
        $title = "";
        switch ($log_type) {
            case LOGTYPE['USER_CREATE']:
                $title = "create new user";
                break;

            case LOGTYPE['USER_UPDATE']:
                // Set data
                $user = $this->getUser();
                $title = "<b>{$user->email}</b> update user";
                break;

            case LOGTYPE['USER_LOGIN']:
                $user = $this->getUser();
                $title = "<b>{$user->email}</b> login to dashboard affiliate";
                break;
        }

        // Log action
        $this->setLog_type($log_type);
        $this->setObject_type(TABLE_USER);
        $this->setLog_change($log_change);
        $log = $this->Save($title);
        return $log;
    }

    public function Log_bidder_unchanged_item($log_type, $log_change = '') {
        // Set data
        $user_id = $this->getUser_id();
        $user = $this->getUser();
        if ($log_change) {
            $log_change = json_encode($log_change);
        }

        // Define $title
        $title = "";
        switch ($log_type) {
            case LOGTYPE['BIDDER_UNCHANGED_ITEM_CREATE']:
                $title = "<b>{$user->email}</b> create new bidder unchanged item";
                break;

            case LOGTYPE['BIDDER_UNCHANGED_ITEM_UPDATE']:
                $title = "<b>{$user->email}</b> update bidder unchanged item";
                break;

            case LOGTYPE['BIDDER_UNCHANGED_ITEM_REMOVE']:
                $title = "<b>{$user->email}</b> remove bidder unchanged item";
                break;

            case LOGTYPE['BIDDER_UNCHANGED_ITEM_UPDATE_API']:
                $title = "<b>{$user->email}</b> update bidder unchanged item by API";
                break;

            case LOGTYPE['BIDDER_UNCHANGED_ITEM_CREATE_API']:
                $title = "<b>{$user->email}</b> create bidder unchanged item by API";
                break;

            case LOGTYPE['BIDDER_UNCHANGED_ITEM_CHANGE_STATUS']:
                $title = "<b>{$user->email}</b> change status bidder unchanged";
                break;
        }

        // Log action
        $this->setLog_type($log_type);
        $this->setObject_id($user_id);
        $this->setObject_type(TABLE_BIDDER_UNCHANGE_ITEM);
        $this->setLog_change($log_change);
        $log = $this->Save($title);
        return $log;
    }

    public function Log_domain_bidder($log_type, $log_change = '') {
        // Set data
        $user_id = $this->getUser_id();
        $user = $this->getUser();
        if ($log_change) {
            $log_change = json_encode($log_change);
        }
        // Define $title
        $title = "";
        switch ($log_type) {
            case LOGTYPE['DOMAIN_BIDDER_CREATE']:
                $title = "<b>{$user->email}</b> create new domain bidder";
                break;

            case LOGTYPE['DOMAIN_BIDDER_UPDATE']:
                $title = "<b>{$user->email}</b> update domain bidder";
                break;

            case LOGTYPE['DOMAIN_BIDDER_UPDATES']:
                $title = "<b>{$user->email}</b> update domain bidder for domains";
                break;

            case LOGTYPE['DOMAIN_BIDDER_REMOVE']:
                $title = "<b>{$user->email}</b> remove domain bidder";
                break;

            case LOGTYPE['DOMAIN_BIDDER_CREATE_ALL']:
                $title = "<b>{$user->email}</b> create new bidder for all domain";
                break;

            case LOGTYPE['DOMAIN_BIDDER_CREATES']:
                $title = "<b>{$user->email}</b> creates new Domain Bidder";
                break;

            case LOGTYPE['DOMAIN_BIDDER_CHECK']:
                $title = "<b>{$user->email}</b> check all bidder for domain";
                break;
        }

        // Log action
        $this->setLog_type($log_type);
        $this->setObject_type(TABLE_DOMAIN_BIDDER);
        $this->setLog_change($log_change);
        $log = $this->Save($title);
        return $log;
    }

    public function Log_bidder_setting_item($log_type, $log_change = '') {
        // Set data
        $user_id = $this->getUser_id();
        $user = $this->getUser();
        if ($log_change) {
            $log_change = json_encode($log_change);
        }


        // Define $title
        $title = "";
        switch ($log_type) {
            case LOGTYPE['BIDDER_SETTING_ITEM_CREATE']:
                $title = "<b>{$user->email}</b> create new bidder setting item";
                break;

            case LOGTYPE['BIDDER_SETTING_ITEM_UPDATE']:
                $title = "<b>{$user->email}</b> update bidder setting item";
                break;

            case LOGTYPE['BIDDER_SETTING_ITEM_REMOVE']:
                $title = "<b>{$user->email}</b> remove bidder setting item";
                break;

            case LOGTYPE['BIDDER_SETTING_ITEM_UPDATE_API']:
                $title = "<b>{$user->email}</b> update bidder setting item by API";
                break;

            case LOGTYPE['BIDDER_SETTING_ITEM_CREATE_API']:
                $title = "<b>{$user->email}</b> create new bidder setting item by API";
                break;
        }

        // Log action
        $this->setLog_type($log_type);
        $this->setObject_type(TABLE_BIDDER_SETTING_ITEM);
        $this->setLog_change($log_change);
        $log = $this->Save($title);
        return $log;
    }

    public function Log_bidder_group($log_type, $log_change = '') {
        // Set data
        $user_id = $this->getUser_id();
        $user = $this->getUser();
        if ($log_change) {
            $log_change = json_encode($log_change);
        }


        // Define $title
        $title = "";
        switch ($log_type) {
            case LOGTYPE['BIDDER_GROUP_CREATE']:
                $title = "<b>{$user->email}</b> create new bidder group";
                break;

            case LOGTYPE['BIDDER_GROUP_UPDATE']:
                $title = "<b>{$user->email}</b> update bidder group";
                break;

            case LOGTYPE['BIDDER_GROUP_REMOVE']:
                $title = "<b>{$user->email}</b> remove bidder group";
                break;

            case LOGTYPE['BIDDER_GROUP_CLONE']:
                $title = "<b>{$user->email}</b> clone bidder group";
                break;

            case LOGTYPE['BIDDER_GROUP_CREATE_FOR_SMART_BANNER']:
                $title = "<b>{$user->email}</b> create new 4 bidder group when create adtag smart banner";
                break;
        }

        // Log action
        $this->setLog_type($log_type);
        $this->setObject_type(TABLE_BIDDER_SETTINGS);
        $this->setLog_change($log_change);
        $log = $this->Save($title);
        return $log;
    }

    public function Log_bidder_global_off($log_type, $log_change = '') {
        // Set data
        $user_id = $this->getUser_id();
        $user = $this->getUser();
        if ($log_change) {
            $log_change = json_encode($log_change);
        }

        // Define $title
        $title = "";
        switch ($log_type) {
            case LOGTYPE['BIDDER_GLOBAL_OFF_CREATE']:
                $title = "<b>{$user->email}</b> create bidder global off";
                break;

            case LOGTYPE['BIDDER_GLOBAL_OFF_UPDATE']:
                $title = "<b>{$user->email}</b> update bidder global off";
                break;

            case LOGTYPE['BIDDER_GLOBAL_OFF_REMOVE']:
                $title = "<b>{$user->email}</b> remove bidder global off";
                break;
        }

        // Log action
        $this->setLog_type($log_type);
        $this->setObject_type(TABLE_BIDDER_GLOBAL_OFF);
        $this->setLog_change($log_change);
        $log = $this->Save($title);
        return $log;
    }

    public function Log_adsense($log_type, $log_change = '') {
        // Set data
        $user_id = $this->getUser_id();
        $user = $this->getUser();
        if ($log_change) {
            $log_change = json_encode($log_change);
        }


        // Define $title
        $title = "";
        switch ($log_type) {
            case LOGTYPE['ADSENSE_CREATE']:
                $title = "<b>{$user->email}</b> create new adsense";
                break;

            case LOGTYPE['ADSENSE_REMOVE']:
                $title = "<b>{$user->email}</b> remove adsense";
                break;
        }

        // Log action
        $this->setLog_type($log_type);
        $this->setObject_type(TABLE_ADSENSE);
        $this->setLog_change($log_change);
        $log = $this->Save($title);
        return $log;
    }

    public function Log_adtag($log_type, $log_change = '') {
        // Set data
        $user_id = $this->getUser_id();
        $user = $this->getUser();
        if ($log_change) {
            $log_change = json_encode($log_change);
        }
        // Define $title
        $title = "";
        switch ($log_type) {
            case LOGTYPE['ADTAG_CREATE']:
                $title = "<b>{$user->email}</b> create new adtag";
                break;

            case LOGTYPE['ADTAG_UPDATE']:
                $title = "<b>{$user->email}</b> update adtag";
                break;

            case LOGTYPE['ADTAG_REMOVE']:
                $title = "<b>{$user->email}</b> remove adtag";
                break;

            case LOGTYPE['ADTAG_WIDGET_ADD']:
                $title = "<b>{$user->email}</b> add widget for adtag";
                break;

            case LOGTYPE['ADTAG_WIDGET_REMOVE']:
                $title = "<b>{$user->email}</b> remove widget for adtag";
                break;

            case LOGTYPE['ADTAG_CREAETE_WHEN_APPROVED_DOMAIN']:
                $title = "<b>{$user->email}</b> create new adtags when approved new domain";
                break;
        }

        // Log action
        $this->setLog_type($log_type);
        $this->setObject_type(TABLE_ADTAGS);
        $this->setLog_change($log_change);
        $log = $this->Save($title);
        return $log;
    }

    public function Log_setting_passback($log_type, $log_change = '') {
        // Set data
        $user_id = $this->getUser_id();
        $user = $this->getUser();
        if ($log_change) {
            $log_change = json_encode($log_change);
        }
        // Define $title
        $title = "";
        switch ($log_type) {
            case LOGTYPE['SETTING_PASSBACK_CREATE']:
                $title = "<b>{$user->email}</b> create new setting passback";
                break;

            case LOGTYPE['SETTING_PASSBACK_UPDATE']:
                $title = "<b>{$user->email}</b> update setting passback";
                break;

            case LOGTYPE['SETTING_PASSBACK_REMOVE']:
                $title = "<b>{$user->email}</b> remove setting passback";
                break;
        }

        // Log action
        $this->setLog_type($log_type);
        $this->setObject_type(TABLE_SETTING_PASSBACK);
        $this->setLog_change($log_change);
        $log = $this->Save($title);
        return $log;
    }

    public function Log_GeoIP($log_type, $log_change = '') {
        // Set data
        $user_id = $this->getUser_id();
        $user = $this->getUser();
        if ($log_change) {
            $log_change = json_encode($log_change);
        }
        // Define $title
        $title = "";
        switch ($log_type) {
            case LOGTYPE['GEOIP_CREATE']:
                $title = "<b>{$user->email}</b> create new GeoIP";
                break;

            case LOGTYPE['GEOIP_UPDATE']:
                $title = "<b>{$user->email}</b> update new GeoIP";
                break;
        }

        // Log action
        $this->setLog_type($log_type);
        $this->setObject_type(TABLE_BIDDER_COUNTRY);
        $this->setLog_change($log_change);
        $log = $this->Save($title);
        return $log;
    }

    public function Log_domain($log_type, $log_change = '') {
        // Set data
        $user_id = $this->getUser_id();
        $user = $this->getUser();
        if ($log_change) {
            $log_change = json_encode($log_change);
        }
        // Define $title
        $title = "";
        switch ($log_type) {
            case LOGTYPE['DOMAIN_CREATE']:
                $title = "<b>{$user->email}</b> create new domain";
                break;

            case LOGTYPE['DOMAIN_UPDATE']:
                $title = "<b>{$user->email}</b> update domain";
                break;

            case LOGTYPE['DOMAIN_REJECT']:
                $title = "<b>{$user->email}</b> reject domain";
                break;
        }

        // Log action
        $this->setLog_type($log_type);
        $this->setObject_type(TABLE_DOMAIN);
        $this->setLog_change($log_change);
        $log = $this->Save($title);
        return $log;
    }

    //RatelogModel
    public function Log_ratelog($log_type, $log_change = '') {
        // Set data
        $user_id = $this->getUser_id();
        $user = $this->getUser();
        if ($log_change) {
            $log_change = json_encode($log_change);
        }
        // Define $title
        $title = "";
        switch ($log_type) {

            case LOGTYPE['RATELOG_UPDATE']:
                $title = "<b>{$user->email}</b> update Ratelog for domain";
                break;
        }

        // Log action
        $this->setLog_type($log_type);
        $this->setObject_type(TABLE_RATEBIDDER);
        $this->setLog_change($log_change);
        $log = $this->Save($title);
        return $log;
    }

    public function Log_payment($log_type, $log_change = '', $table = TABLE_PAYMENT) {
        // Set data
        $user_id = $this->getUser_id();
        $user = $this->getUser();
        if ($log_change) {
            $log_change = json_encode($log_change);
        }
        // Define $title
        $title = "";
        switch ($log_type) {
            case LOGTYPE['PAYMENT_CREATE']:
                $title = "<b>{$user->email}</b> create new payment";
                break;

            case LOGTYPE['PAYMENT_UPDATE']:
                $title = "<b>{$user->email}</b> update payment";
                break;

            case LOGTYPE['PAYMENT_PAID']:
                $title = "<b>{$user->email}</b> payment paid";
                break;

            case LOGTYPE['PAYMENT_SCAN']:
                $title = "<b>{$user->email}</b> payment scan";
                break;

            case LOGTYPE['PAYMENT_REQUEST_CREATE']:
                $title = "<b>{$user->email}</b> create new payment request";
                break;

            case LOGTYPE['PAYMENT_REQUEST_UPDATE']:
                $title = "<b>{$user->email}</b> update payment request";
                break;
        }

        // Log action
        $this->setLog_type($log_type);
        $this->setObject_type($table);
        $this->setLog_change($log_change);
        $log = $this->Save($title);
        return $log;
    }

    public function Log_report_by_hand($log_type, $log_change = '') {
        // Set data
        $user_id = $this->getUser_id();
        $user = $this->getUser();
        if ($log_change) {
            $log_change = json_encode($log_change);
        }
        // Define $title
        $title = "";
        switch ($log_type) {
            case LOGTYPE['REPORT_BY_HAND']:
                $title = "<b>{$user->email}</b> clone report by hand";
                break;
        }

        // Log action
        $this->setLog_type($log_type);
        $this->setObject_type(TABLE_REPORT_TAG . ', ' . TABLE_REPORT_TAG_FOR_PUBLISHER . ', ' . TABLE_REPORT_BIDDER . ', ' . TABLE_REPORT_BIDDER_FOR_PUBLISHER);
        $this->setLog_change($log_change);
        $log = $this->Save($title);
        return $log;
    }

    /**
     * Save all log of system
     * @param string $title
     * @return boolean|array
     */
    private function Save($title = '') {

        // Set data for insert log
        $user_id = $this->getUser_id();
        $object_id = $this->getObject_id();
        $object_type = $this->getObject_type();
        $log_type = $this->getLog_type();
        $log_condition = $this->getLog_condition();
        $log_change = $this->getLog_change();
        $log_last_data = $this->getLog_last_data();
        $data_insert = [
            'title' => $title,
            'user_id' => $user_id,
            'object_id' => $object_id,
            'object_type' => $object_type,
            'log_type' => $log_type,
            'log_condition' => $log_condition,
            'log_change' => $log_change,
            'log_last_data' => $log_last_data,
            'created_at' => time(),
        ];

        // Validate data
        $validate = $this->validate($data_insert);
        if (isset($validate['error'])) {
            return $validate;
        }

        $insert = $this->insert($data_insert);
        return $insert;
    }

    /**
     * Validate data insert.
     * @param array $inputs
     * @return boolean|array
     */
    private function validate($inputs) {
        $error = false;
        if (!$error && empty($inputs['user_id'])) {
            $error = 'UserID is requeid!!!';
        }
        if (!$error && empty($inputs['object_id'])) {
            $error = 'ObjectID is requeid!!!';
        }
        if (!$error && empty($inputs['object_type'])) {
            $error = 'ObjectType is requeid!!!';
        }
        if (!$error && empty($inputs['log_type'])) {
            $error = 'LogType is requeid!!!';
        }
        if ($error) {
            return error($error);
        }
        return true;
    }

    function getUser() {
        return $this->user;
    }

    function getUser_id() {
        return $this->user_id;
    }

    function getObject_id() {
        return $this->object_id;
    }

    function getObject_type() {
        return $this->object_type;
    }

    function getLog_type() {
        return $this->log_type;
    }

    function getLog_condition() {
        return $this->log_condition;
    }

    function getLog_change() {
        return $this->log_change;
    }

    function getLog_last_data() {
        return $this->log_last_data;
    }

    function setUser($user) {
        if (!$user) {
            return false;
        }
        $this->user = $user;
        $this->setUser_id($user->id);
        return $this;
    }

    function setUser_id($user_id) {
        $this->user_id = $user_id;
        return $this;
    }

    public function setObject($id, $type) {
        $this->setObject_id($id);
        $this->setObject_type($type);
        return $this;
    }

    function setObject_id($object_id) {
        $this->object_id = $object_id;
        return $this;
    }

    function setObject_type($object_type) {
        $this->object_type = $object_type;
        return $this;
    }

    function setLog_type($log_type) {
        $this->log_type = $log_type;
        return $this;
    }

    function setLog_condition($log_condition) {
        if (is_array($log_condition)) {
            $log_condition = json_encode($log_condition);
        }
        $this->log_condition = $log_condition;
        return $this;
    }

    function setLog_change($log_change) {
        $this->log_change = $log_change;
        return $this;
    }

    function setLog_last_data($log_data) {
        $this->log_last_data = json_encode($log_data);
        return $this;
    }

}
