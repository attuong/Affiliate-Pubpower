<?php
ini_set('display_errors', 1);
ini_set('error_reporting', 22527);
ini_set('memory_limit', '256M');
ini_set('serialize_precision', 14);

$application = isset($_COOKIE['app_version']) && in_array($_COOKIE['app_version'], ['dev']) ? 'dashboard_dev' : 'dashboard_v1';
//$application = 'dashboard_v1';

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../system'));
define('PUBLIC_PATH', realpath(dirname(__FILE__)));
define('STATIC_PATH', PUBLIC_PATH . '/static');
//define('STATIC_PATH', '/home/value/domains/static.valueimpression.com/public_html');
define('LIBRARY_PATH', ROOT_PATH . '/libraries');
define('APPLICATION', $application);
define('APPLICATION_PATH', ROOT_PATH . '/applications');
define('APPLICATION_RUN_PATH', APPLICATION_PATH . '/' . APPLICATION);
define('TEMPLATE_PATH', APPLICATION_RUN_PATH . '/templates');
define('DOMAIN', preg_replace("/www\./", "", $_SERVER['HTTP_HOST']));
define('ROOTDOMAIN', getProtocol() . DOMAIN);

$set_default_mod = false;
$set_default_col = false;
$set_default_act = false;

include ROOT_PATH . '/system.php';
exit();

/**
 * getProtocol
 * @return type
 */
function getProtocol() {
    $isSecure = false;
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $isSecure = true;
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $isSecure = true;
    } elseif (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
        $isSecure = true;
    }
    return $isSecure ? 'https://' : 'http://';
}
