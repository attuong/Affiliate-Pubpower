<?php

$already_there = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
$request_uri = str_replace($_SERVER['SCRIPT_NAME'], "", $_SERVER['REQUEST_URI']);
if (isset($_SERVER['REDIRECT_QUERY_STRING']) && $_SERVER['REDIRECT_QUERY_STRING']) {
    $request_uri = str_replace("?" . $_SERVER['REDIRECT_QUERY_STRING'], "", $_SERVER['REQUEST_URI']);
} elseif (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) {
    $request_uri = str_replace("?" . $_SERVER['QUERY_STRING'], "", $_SERVER['REQUEST_URI']);
}

$uri = substr($request_uri, 1);
$reverse = strrev($uri);
if (isset($reverse{0}) && $reverse{0} == '/') {
    $uri = substr($uri, 0, -1);
}
$uri_explode = explode("/", $uri);
if ($uri && $uri != APPLICATION) {
    $_GET['mod'] = $_REQUEST['mod'] = $configs['default']['mod'];
    $_GET['col'] = $_REQUEST['col'] = false;
    $_GET['act'] = $_REQUEST['act'] = false;
} else {
    $_GET['mod'] = $_REQUEST['mod'] = $configs['default']['mod'];
    $_GET['col'] = $_REQUEST['col'] = $configs['default']['col'];
    $_GET['act'] = $_REQUEST['act'] = $configs['default']['act'];
}

if (isset($subfoder) && $subfoder == $uri_explode[0]) {
    unset($uri_explode[0]);
    $uri_explode = array_values($uri_explode);
}

if (isset($uri_explode[0]) && $uri_explode[0] && isset($uri_explode[1]) && $uri_explode[1] && isset($uri_explode[2]) && $uri_explode[2]) {
    $_GET['mod'] = $_REQUEST['mod'] = $uri_explode[0];
    $_GET['col'] = $_REQUEST['col'] = $uri_explode[1];
    $_GET['act'] = $_REQUEST['act'] = $uri_explode[2];
} elseif (isset($uri_explode[0]) && $uri_explode[0] && isset($uri_explode[1]) && $uri_explode[1]) {
    $_GET['mod'] = $_REQUEST['mod'] = $configs['default']['mod'];
    $_GET['col'] = $_REQUEST['col'] = $uri_explode[0];
    $_GET['act'] = $_REQUEST['act'] = $uri_explode[1];
} elseif (isset($uri_explode[0]) && $uri_explode[0]) {
    $is_query = substr($uri_explode[0], 0, 1);
    if ($is_query == '?') {
        $_GET['mod'] = $_REQUEST['mod'] = $configs['default']['mod'];
        $_GET['col'] = $_REQUEST['col'] = $configs['default']['col'];
        $_GET['act'] = $_REQUEST['act'] = $configs['default']['act'];
    }
}
if (isset($_routers) && $_routers) {
    foreach ($_routers as $regex => $mapper) {
        if (preg_match("(^$regex$)is", $uri, $matches) && $uri) {
            foreach ($matches as $key => $val) {
                if (!is_numeric($key)) {
                    $_GET['mod'] = $_REQUEST['mod'] = $mapper['mod'];
                    $_GET['col'] = $_REQUEST['col'] = $mapper['col'];
                    $_GET['act'] = $_REQUEST['act'] = $mapper['act'];
                } elseif (count($matches) >= 1) {
                    $_GET['mod'] = $_REQUEST['mod'] = $mapper['mod'];
                    $_GET['col'] = $_REQUEST['col'] = $mapper['col'];
                    $_GET['act'] = $_REQUEST['act'] = $mapper['act'];
                    if (isset($mapper['map'])) {
                        foreach ($matches as $k => $v) {
                            if ($k == 0) {
                                continue;
                            }
                            if (isset($mapper['map'][$k])) {
                                $_GET[$mapper['map'][$k]] = $v;
                                $_REQUEST[$mapper['map'][$k]] = $v;
                            }
                        }
                    }
                }
            }
            break;
        }
    }
    if (isset($subfoder) && $uri == $subfoder) {
        $uri = "";
    }
} else {
    $uri = "";
}
