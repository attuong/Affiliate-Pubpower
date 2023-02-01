<?php
//if (DOMAIN != 'console.valueimpression.local') {
//    opcache_reset();
//}

/* @var $classCalling core\Controller */
if ($configs['debug']) {
    ini_set('display_errors', 1);
}

use core\Template;

include APPLICATION_RUN_PATH . '/functions.php';

/* @var $Template core\Template */
$Template = Template::getInstance();
$assign['Template'] = $Template;

//=> Assign data default to controller
$classCalling->module = $assign['module'] = $module;
$classCalling->controller = $assign['controller'] = $controller;
$classCalling->action = $assign['action'] = $action;

$classCalling->pagination->setLimit(15);
$assign['pagination'] = $classCalling->pagination;
$assign['search_input'] = \core\Input::getInstance()->get('q');

//=> Init variable
$user_login = false;
$customer_login = false;
$guestID = $assign['guestID'] = guestID();
$user_query_string = $assign['user_query_string'] = user_query_string();
$URLSchemes = $assign['URLSchemes'] = "$module/$controller/$action";
$URLSchemesController = $assign['URLSchemesController'] = "$module/$controller";

//=> Mobile Detect
$detect = new \library\MobileDetect();
$device = $assign['device'] = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$platform = $assign['platform'] = $detect->isiOS() ? 'ios' : ($detect->isAndroidOS() ? 'android' : 'unknown');
$user_agent = $assign['user_agent'] = $detect->getUserAgent();
$backurl = urldecode(\core\Input::getInstance()->get('backurl'));
$app_version = $assign['app_version'] = isset($_COOKIE['app_version']) && in_array($_COOKIE['app_version'], ['dev']) ? 'dev' : 'rele';
$theme_settings = $assign['theme_settings'] = $configs['theme_settings'][$app_version];
if (!$backurl) {
    $backurl = URL_DASHBOARD;
}
$classCalling->backurl = $assign['backurl'] = $backurl;
$classCalling->guestID = $guestID;
$classCalling->user_query_string = $user_query_string;
$classCalling->device = $device;
$classCalling->platform = $platform;
$classCalling->user_agent = $user_agent;
$classCalling->app_version = $app_version;
$classCalling->theme_settings = $theme_settings;

BidderBlock::getInstance()->device = $device;

switch ($module) {

    case 'api':

        $theme_folder = $assign['theme_folder'] = $configs['theme_folder'];

        break;

    default: // MODULE DEFAULT (Backend)

        $theme_folder = $assign['theme_folder'] = $configs['theme_folder'];

        // Login & check Login
        $user_login = \UserModel::getInstance()->get_user_login();
        if (!$user_login && !in_array($URLSchemes, ['frontend/Home/login', 'frontend/Home/logout', 'frontend/Home/register'])) {
            redirect(URL_LOGIN);
        }

        $classCalling->user_login = $assign['user_login'] = $user_login;
        ActivityModel::getInstance()->setUser($user_login);

        // Set Title/ Description default
        $classCalling->setTitle(DEFAULT_TITLE);
        $classCalling->setDescription(DEFAULT_DESCRIPTION);
        // SET <meta> tags
        $Template->setMultiMetaTags([
            ['property' => 'og:url', 'content' => strtok(current_url(), '?')],
            ['property' => 'og:type', 'content' => 'website'],
            ['property' => 'og:image', 'content' => ROOTDOMAIN . DEFAULT_OG_IMAGE],
            ['property' => 'og:locale', 'content' => 'vi_VN'],
            ['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0, maximum-scale=2'],
            ['name' => 'theme-color', 'content' => '#141414'],
        ]);
        // Set <link> tags
        $Template->setMultiLinkTags([
            ['rel' => 'alternate', 'href' => current_url(), 'hreflang' => 'vi-vn'],
            ['rel' => 'shortcut icon', 'href' => '/themes/' . $theme_folder . '/assets/img/favicon.png', 'type' => 'image/x-icon'],
        ]);
        break;
}

// TEMPLATE HANDLING
include APPLICATION_RUN_PATH . "/templates/_routers/theme.$theme_folder.php";
$Template->setAssign($assign);
$Template->setThemeFolder($theme_folder);
