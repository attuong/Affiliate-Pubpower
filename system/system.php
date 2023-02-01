<?php

use core\Template;
use core\Input;

session_start();
header('Content-Type: text/html; charset=utf-8');

require ROOT_PATH . '/cores/class.autoload.php';
new \core\autoRun();
/* @var $Template core\Template */
$Template = Template::getInstance();

require ROOT_PATH . '/cores/function.commons.php';
require ROOT_PATH . '/configs.php';
require ROOT_PATH . '/routers.php';
require ROOT_PATH . '/cores/inc.router.php';

$module = Input::getInstance()->get('mod');
$controller = ucfirst(Input::getInstance()->get('col'));
$action = Input::getInstance()->get('act');

$controllerClassname = "{$controller}Controller";
$actionName = "{$action}Action";
$application_running = APPLICATION_RUN_PATH . "/modules/$module/$controllerClassname.php";

if (is_file($application_running)) {

    if ($Template->status) {
        $Template->setThemeFolder($configs['theme_folder']);
        $Template->setModule($module);
        $Template->setControler($controller);
        $Template->setAction($action);
        $Template->setAssign($configs);
    }

    $classCallingName = "controller\\$module\\$controllerClassname";
    $classCalling = $classCallingName::getInstance();

    /**
     * Load file bootstrap
     */
    $bootstrap_running = APPLICATION_RUN_PATH . "/bootstrap.php";
    if (file_exists($bootstrap_running)) {
        require $bootstrap_running;
    }

    /*
     * RUN
     */
    if (method_exists($classCalling, $actionName)) {
        $classCalling->$actionName();
    } else {
        $Template->page_not_found(['message' => '<strong>Error message:</strong> <span style="color:red">Action does not exist!</span>', 'message_type' => ERROR, 'debug' => $configs['debug']]);
    }
} else {
    $Template->page_not_found(['message' => '<strong>Error message:</strong> <span style="color:red">Controller does not exist!</span>', 'message_type' => ERROR, 'debug' => $configs['debug']]);
}
exit();
