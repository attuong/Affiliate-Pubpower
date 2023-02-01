<?php

namespace core;

use core\Loader;
use core\Input;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controller
 *
 * @author TungDT
 * 
 */
class Controller {

    public static $instance = [];
    public $assign = [];
    public $css;
    private $_values;

    /**
     * Extend
     * 
     */
    public $user_login;
    public $guestID;
    public $backurl;
    public $module;
    public $controller;
    public $action;

    /**
     *
     * @var \core\Loader $load
     */
    public $load;

    /**
     *
     * @var \core\Template $template 
     */
    public $template;

    /**
     *
     * @var \core\Input $input 
     */
    public $input;

    /**
     *
     * @var \core\Pagination $pagination 
     */
    public $pagination;

    public function __construct() {
        $this->load = Loader::getInstance();
        $this->input = Input::getInstance();
        $this->pagination = Pagination::getInstance(15);
        $this->template = $this->load->template;

        $this->assign['message'] = false;
        $this->assign['message_type'] = false;
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

    /**
     * 
     * @return object
     */
    public static function getInstance() {
        $current_class_name = get_called_class();
        if (!isset(static::$instance[$current_class_name]) || null === static::$instance[$current_class_name]) {
            static::$instance[$current_class_name] = new static();
        }
        return static::$instance[$current_class_name];
    }

    /**
     * Include file Javascript
     * @param array $inputs
     * @param string $key
     * @return boolean
     */
    public function setJavascript($inputs, $key = 'default') {
        if ($inputs) {
            $tags_infos = [];
            foreach ($inputs as $url) {
                $tags_infos[] = ['src' => $url];
            }
            $this->template->setMultiJavascriptTags($tags_infos, $key);
        }
        return false;
    }

    /**
     * Include file CSS
     * @param array $inputs
     * @return boolean
     */
    public function setCSS($inputs) {
        if ($inputs) {
            $tags_infos = [];
            foreach ($inputs as $url) {
                $tags_infos[] = ['rel' => 'stylesheet', 'href' => $url, 'type' => 'text/css'];
            }
            $this->template->setMultiLinkTags($tags_infos);
            return true;
        }
        return false;
    }

    /**
     * set <title>
     * @param string $title
     * @param boolean $social
     */
    public function setTitle($title = "", $social = true) {
        $this->template->setTitle($title);
        $this->template->setMetaTags(['name' => 'title', 'content' => $title]);
        if ($social) {
            $this->template->setMetaTags(['property' => 'og:title', 'content' => $title]);
        }
    }

    /**
     * Set <meta> Description
     * @param string $description
     * @param boolean $social
     */
    public function setDescription($description = "", $social = true) {
        $this->template->setMetaTags(['name' => 'description', 'content' => $description]);
        if ($social) {
            $this->template->setMetaTags(['property' => 'og:description', 'content' => $description]);
        }
    }

    /**
     * Set <meta> Keywords
     * @param array $keywords
     */
    public function setKeywords($keywords) {
        $this->template->setMetaTags(['name' => 'keywords', 'content' => $keywords]);
    }

}
