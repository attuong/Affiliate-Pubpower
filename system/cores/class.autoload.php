<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace core;

/**
 * Description of class
 *
 * @author TungDT
 */
class autoRun {

    public function __construct() {
        spl_autoload_register(array($this, "myAutoload"), true, true);
    }

    public function myAutoload($class_name) {
        $file_path = str_replace(['core\\', '\\'], ['', '/'], ROOT_PATH . "/cores/class.base." . strtolower($class_name)) . ".php";
        if (!is_file($file_path)) {
            $file_path = str_replace(['controller\\', '\\'], ['', '/'], APPLICATION_RUN_PATH . "/modules/" . $class_name) . ".php";
        }
        if (!is_file($file_path)) {
            $file_path = str_replace(['library\\', '\\'], ['', '/'], LIBRARY_PATH . "/" . $class_name) . ".php";
        }
        if (!is_file($file_path)) {
            $file_path = APPLICATION_RUN_PATH . "/blocks/" . $class_name . ".php";
        }
        if (!is_file($file_path)) {
            $file_path = APPLICATION_RUN_PATH . "/models/" . $class_name . ".php";
        }
        if (!is_file($file_path)) {
            return false;
        }
        include_once $file_path;
    }

}
