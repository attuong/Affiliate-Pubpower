<?php

ini_set('error_reporting', 22527);
ini_set('display_errors', 1);
date_default_timezone_set('America/New_York');

define('ROOT_PATH', realpath(dirname(__FILE__)) . '/../..');
define('LIBRARY_PATH', ROOT_PATH . '/libraries');
define('APPLICATION', 'dashboard');
define('APPLICATION_PATH', ROOT_PATH . '/applications');
define('APPLICATION_RUN_PATH', APPLICATION_PATH . '/' . APPLICATION);
define('DOMAIN', preg_replace("/www\./", "", 're.valueimpression.com'));
define('ROOTDOMAIN', 'https://' . DOMAIN);

require ROOT_PATH . '/cores/class.autoload.php';
new \core\autoRun();

require ROOT_PATH . '/cores/function.commons.php';
require ROOT_PATH . '/configs.php';

function sendData($data, $type){
    $random = rand(0, 9999);
    $token = md5('VliSystem' . $random);
    $field = [
        'data' => $data,
        'type' => $type
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://ai.valueimpression.com/api/sync/syncdata");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($field));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-Request: ' . $token,
        'X-Key: ' . $random,
    ));
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}