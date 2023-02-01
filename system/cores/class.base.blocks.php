<?php

namespace core;

use core\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author TungDT
 */
class Blocks extends Controller {

    /** @var $instance Blocks */
    public static $instance;
    private $_values;

    public function __construct() {
        parent::__construct();
    }

    public static function getInstance() {
        $current_class_name = get_called_class();
        if (!isset(static::$instance[$current_class_name]) || null === static::$instance[$current_class_name]) {
            static::$instance[$current_class_name] = new static();
        }
        return static::$instance[$current_class_name];
    }

    public function __set($key, $value) {
        $this->_values[$key] = $value;
    }

    public function __get($key) {
        if (isset($this->_values[$key])) {
            return $this->_values[$key];
        }
        return false;
    }

    public function render($path, $assign = []) {
        $file_running = APPLICATION_RUN_PATH . "/blocks/views/" . $path;
        if (is_file($file_running)) {
            ob_start();
            if ($assign) {
                extract($assign, EXTR_OVERWRITE);
            }
            require $file_running;
            $content_template = ob_get_contents();
            ob_end_clean();
            return $content_template;
        }
        return false;
    }

    public static function display($path, $assign = []) {
        echo $this->render($path, $assign);
    }

}
