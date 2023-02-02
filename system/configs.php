<?php

date_default_timezone_set('America/New_York');
define('TIME_ZONE', 'America/New_York');

$configs = [];

$configs['debug'] = 0;
if (isset($_COOKIE['_admin'])) {
    $configs['debug'] = 1;
}

$configs['default']['mod'] = isset($set_default_mod) && $set_default_mod ? $set_default_mod : 'frontend';
$configs['default']['col'] = isset($set_default_col) && $set_default_col ? $set_default_col : 'home';
$configs['default']['act'] = isset($set_default_act) && $set_default_act ? $set_default_act : 'index';

$configs['theme_folder'] = 'atlantis';

$configs['is_local'] = $is_local = (DOMAIN === 'aff.valueimpression.local') ? true : false;
if ($is_local) {
    $_theme_setting_dev = (object) [
                'logo' => 'purple2',
                'logo_sup' => '#fff',
                'navbar' => 'purple',
                'sidebar' => 'purple2',
                'sidebar_nav' => 'nav-default',
                'clock_text' => '#eaeaea',
                'card' => 'card-secondary',
                'card_bg' => 'bg-secondary-gradient',
                'card_header' => 'bg-secondary',
                'panel_header' => 'bg-secondary-gradient',
                'table_head' => 'table-head-bg-secondary',
                'btn' => 'btn-secondary',
                'btn_border' => 'btn-secondary',
                'headline' => 'Backend local version for developers'
    ];
    $_theme_setting_rele = (object) [
                'logo' => 'dark',
                'logo_sup' => '#f66',
                'navbar' => 'dark',
                'sidebar' => 'dark2',
                'sidebar_nav' => 'nav-default',
                'clock_text' => '#eaeaea',
                'card' => 'card-dark',
                'card_bg' => 'bg-info-gradient',
                'card_header' => 'bg-dark-gradient',
                'panel_header' => 'bg-dark-gradient',
                'table_head' => 'table-head-bg-dark',
                'btn' => 'btn-dark',
                'btn_border' => 'btn-default',
                'headline' => 'The world\'s leading real-time bidding advertising system!'
    ];
} else {
    $_theme_setting_dev = (object) [
                'logo' => 'dark',
                'logo_sup' => '#fff',
                'navbar' => 'dark2',
                'sidebar' => 'dark',
                'sidebar_nav' => 'nav-default',
                'clock_text' => '#eaeaea',
                'card' => 'card-dark',
                'card_bg' => 'bg-info-gradient',
                'card_header' => 'bg-dark-gradient',
                'panel_header' => 'bg-dark-gradient',
                'table_head' => 'table-head-bg-dark',
                'btn' => 'btn-dark',
                'btn_border' => 'btn-default',
                'headline' => 'The world\'s leading real-time bidding advertising system!'
    ];
    $_theme_setting_rele = (object) [
                'logo' => 'blue',
                'logo_sup' => '#fff',
                'navbar' => 'blue2',
                'sidebar' => 'white',
                'sidebar_nav' => 'nav-primary',
                'clock_text' => '#eaeaea',
                'card' => 'card-primary',
                'card_bg' => 'bg-primary-gradient',
                'card_header' => 'bg-primary',
                'panel_header' => 'bg-primary-gradient',
                'table_head' => 'table-head-bg-primary',
                'btn' => 'btn-primary',
                'btn_border' => 'btn-primary',
                'headline' => 'Backend server version for developers'
    ];
}

/**
 * Setting color for themes
 */
$configs['theme_settings'] = [
    'rele' => $_theme_setting_rele,
    'dev' => $_theme_setting_dev
];

// Config Default Site Info
define('DEFAULT_TITLE', 'Affiliate');
define('DEFAULT_DESCRIPTION', '');
define('DEFAULT_KEYWORDS', '');
define('DEFAULT_LOGO_URL', ROOTDOMAIN . '/images/logo.png');
define('DEFAULT_OG_IMAGE', ROOTDOMAIN . '/images/logo.png');

// Config MySQL
if ($is_local) {
    define('DB_USER', 'apacadmin');
    define('DB_PASSWORD', 'iK29&6%!9XKjs@');
    define('DB_NAME', 'apac_ss');
    define('DB_HOST', 'p:localhost');
    define('DB_PORT', 3306);
    define('DB_ENCODING', 'utf8mb4');
} else {
    define('DB_USER', 'apacadmin');
    define('DB_PASSWORD', 'iK29&6%!9XKjs@');
    define('DB_NAME', 'apac_ss');
    // define('DB_HOST', '192.168.9.10');
    define('DB_HOST', 'p:localhost');
    define('DB_PORT', 9682);
    //define('DB_ENCODING', 'utf8_general_ci');
    define('DB_ENCODING', 'utf8mb4');
}

// Config Table Database MySQL
define('TABLE_USER', 'user');
// define('TABLE_AFF_LEVEL', 'hbd_aff_level');
define('TABLE_BILLING_USER', 'user_billing');
define('TABLE_REPORT_PUB', 'report_pub');
// define('TABLE_REPORT_TAG_FOR_PUBLISHER', 'hbd_reports_tag_user');
define('TABLE_PAYMENT', 'payment_invoice');
define('TABLE_DOMAIN', 'inventory');
define('TABLE_PAYMENT_REQUEST', 'payment_request');
define('TABLE_ACTIVITY', 'hbd_activity');

// Config URL
define('DOMAIN_MASTER', 'apps.pubpower.io');
// define('DOMAIN_AD', 'services.bilsyndication.com');
// define('DOMAIN_CONSOLE', 'console.valueimpression.com');
// define('URL_CONSOLE', '//console.valueimpression.com');
// define('URL_CONSOLE_AUTOLOGIN', '//console.valueimpression.com/home/atl');
// define('URL_VLI', '//valueimpression.com');
define('URL_STATIC', '//assets.bilsyndication.com');
// define('URL_DASHBOARD_REGISTER', 'dashboard.valueimpression.com/Register');
define('URL_DASHBOARD', ROOTDOMAIN);
// define('URL_ADS', '//' . DOMAIN_AD);
// define('URL_ADSENSE', '/adsense/index?f_step%5B%5D=5&f_step%5B%5D=4&f_step%5B%5D=3&f_step%5B%5D=2&f_step%5B%5D=1');
define('URL_LOGIN', '/login');
define('URL_LOGOUT', '/logout');
define('URL_USER', '/user/setting');
define('URL_USER_BILLING', '/user/billing');
define('URL_USER_CREATE', '/user/create');
define('URL_USER_AUTO_LOGIN', '/user/autologin');
define('URL_REPORT', '/report');
define('URL_DOMAIN', '/domain');
define('URL_PAYMENT', '/payment');
define('URL_LINK_AFFILIATE', '/link');
define('URL_AUTO_LOGIN', '/Login/');

// define('URL_BIDDER_CHANGE_DEMAND_STATUS', '/bidder/change_demand_status');

define('URL_AJAX', '/ajax');
define('URL_AJAX_DOMAIN_SEARCH', '/ajax/domain/search');

// Druid report
define("LINK_DRUID_REPORT", "https://query.vliplatform.com/druid/v2/sql/");
define("AUTHORIZATION_DRUID", "Basic cmVhZG1hbjpMb3hpVVNKISEoODIhQA==");

/**
 * CONST
 */
define('LAYOUT_EMPTY', 'tpl.layout_empty.php');
define('KEY_HANDLE_PASSWORD', "as3df!");
define('KEY_LOGIN_TOKEN', 'ee@!3xx1q#1r');

define('EDIT', 'EDIT');
define('REMOVE', 'REMOVE');
define('DELETE', 'DELETE');
define('SUCCESS', 'SUCCESS');
define('WARNING', 'WARNING');
define('ERROR', 'ERROR');

// Config API
define('GG_API_KEY', 'AIzaSyDP-k3V6h1ly9oQNfFWFl8MSkzDdvjVtLs');
define('GG_CLIENT_ID', '596844352556-vnkrqbnrvphtgtu3vg74ef5e3uc0n0d5.apps.googleusercontent.com');
define('GG_CLIENT_SECRET', 'xrP6-e1iiGlhUvcRDXfDcQdG');
define('FB_APP_ID', '143417543000115');
define('FB_APP_SECRET', '22f7a5dedd5f6f150ed1f02feba42bfe');
define('TRACKING_ID', 'UA-109910709-1');
define('MJ_APIKEY_PUBLIC', '5e8f0dc0d0d3c031be0af74e088e9f65');
define('MJ_APIKEY_PRIVATE', 'b870d85baf0df970517293127596d785');
define('MAILCHIMP_API_KEY', 'a830282ec6c9109ec3cff3d8c7cdd31f-us19');
