<?php

namespace core;

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
class Input {

    /** @var $instance Input */
    public static $instance = null;

    public function __construct() {
        
    }

    /**
     * 
     * @return object
     */
    public static function getInstance() {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * 
     * @param string|integer $key
     * @return array|boolean
     */
    public function file($key = FALSE) {
        if (!$key) {
            if (empty($_FILES)) {
                return FALSE;
            } else {
                return TRUE;
            }
            return FALSE;
        } else {
            if (isset($_FILES[$key]) && $_FILES[$key]) {
                return $_FILES[$key];
            }
            return false;
        }
    }

    /**
     * 
     * @param string|integer $key
     * @return string|array|boolean
     */
    public function post($key = FALSE) {
        if (!$key) {
            if (empty($_POST)) {
                return FALSE;
            } else {
                return TRUE;
            }
            return FALSE;
        } else {
            if (isset($_POST[$key]) && $_POST[$key]) {
                return $_POST[$key];
            }
            return false;
        }
    }

    /**
     * 
     * @param string|integer $key
     * @return string|array|boolean
     */
    public function get($key = FALSE) {
        if (!$key) {
            if (empty($_GET)) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            if (isset($_GET[$key]) && $_GET[$key]) {
                return $_GET[$key];
            }
            return false;
        }
    }

    /**
     * 
     * @param string|integer $key
     * @return string|array|boolean
     */
    public function request($key = FALSE) {
        if (!$key) {
            if (empty($_REQUEST)) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            if (isset($_REQUEST[$key]) && $_REQUEST[$key]) {
                return $_REQUEST[$key];
            }
            return false;
        }
    }

    public function getAllPost($keys = array()) {
        $results = array();
        if (is_array($keys) && $keys) {
            foreach ($keys as $key) {
                $results[$key] = $this->post($key);
            }
        } else {
            if (!empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    $results[$key] = $value;
                }
            }
        }
        return $results;
    }

    public function getAllRequest($keys = array()) {
        $results = array();
        if (is_array($keys) && $keys) {
            foreach ($keys as $key) {
                $results[$key] = $this->request($key);
            }
        } else {
            if (!empty($_REQUEST)) {
                foreach ($_REQUEST as $key => $value) {
                    $results[$key] = $value;
                }
            }
        }
        return $results;
    }

    public function filters($prefix_key = 'f_') {
        $results = [];
        if (!empty($_REQUEST)) {
            $number_character = strlen($prefix_key);
            foreach ($_REQUEST as $key => $value) {
                $substr = substr($key, 0, $number_character);
                if ($substr === $prefix_key) {
                    $new_key = str_replace($prefix_key, '', $key);
                    $results[$new_key] = $value;
                }
            }
        }
        return $results;
    }

}
