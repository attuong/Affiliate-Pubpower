<?php

namespace core;

use core\Template;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author TungDT
 * 
 */
class Loader {

    /** @var $instance Loader */
    public static $instance = null;

    /**
     *
     * @var \core\Template $template 
     */
    public $template;
    protected $models;

    public function __construct() {
        $this->template = Template::getInstance();
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
     * @param string $model
     * @return object|boolean
     */
    public function model($model) {
        if (substr($model, -5) == "Model") {
            $model_name = $model;
        } else {
            $model_name = $model . "Model";
        }
        if (isset($this->models[$model_name])) {
            return $this->models[$model_name];
        }
        $path = APPLICATION_RUN_PATH . "/models/" . $model_name . ".php";
        if (!is_file($path)) {
            return false;
        }
        include_once $path;
        $this->models[$model_name] = $model_name::getInstance();
        return $this->models[$model_name];
    }

    public function library($lib) {
        $path = LIBRARY_PATH . "/inc.$lib.php";
        include_once $path;
    }

    /**
     * 
     * @param array $assign
     * @param string $file_path
     */
    public function view($assign = [], $file_path = null) {
        $this->template->setAssign($assign);
        $this->template->setFilePath($file_path);
        $this->template->run();
    }

    /**
     * 
     * @param type $assign
     */
    public function page_not_found($assign = ['message' => '', 'message_type' => '']) {
        header("HTTP/1.0 404 Not Found");
        $file_path = "{$this->template->root_path}/{$this->template->theme_folder}/tpl.404.php";
        $this->view($assign, $file_path);
    }

    public function permission_denied($assign = ['message' => '', 'message_type' => '']) {
        header("HTTP/1.0 404 Not Found");
        $file_path = "{$this->template->root_path}/{$this->template->theme_folder}/tpl.permission_denied.php";
        $this->view($assign, $file_path);
    }

}
