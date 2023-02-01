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
class Template {

    /** @var $instance Template */
    public static $instance = null;
    public $assign = [];
    public $layout_file_name = 'tpl.layout.php';
    public $layout_file_path = null;
    public $error_file_name = 'tpl.404.php';
    public $error_file_path = null;
    public $file_path = null;
    public $status = true;
    public $module = null;
    public $controller = null;
    public $action = null;
    public $theme_folder = null;
    public $root_path = null;
    public $meta_tags = [];
    public $link_tags = [];
    public $javascript_tags = [];
    public $title;
    public $description;
    public $keywords;
    public $javascripts;

    public function __construct() {
        $this->root_path = TEMPLATE_PATH;
    }

    public static function getInstance() {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Display views
     */
    public function run() {
        if ($this->file_path) {
            $file_running = $this->file_path;
        } else {
            $file_running = "$this->root_path/$this->theme_folder/$this->module/$this->controller/tpl.$this->action.php";
        }
        if (is_file($file_running)) {
            ob_start();
            if ($this->assign) {
                extract($this->assign, EXTR_OVERWRITE);
            }
            require_once $file_running;
            $content_template = ob_get_contents();
            ob_end_clean();
            require_once $this->layout_file_path;
        } else {
            $this->assign['message'] = 'File template not found<br/><b>' . $file_running . '</b>';
            $this->assign['message_type'] = ERROR;
            $this->page_not_found();
        }
    }

    /**
     * Display page error, 404,...
     */
    public function page_not_found($assign = []) {
        header("HTTP/1.0 404 Not Found");
        $this->assign = array_merge($this->assign, $assign);
        if ($this->assign) {
            extract($this->assign, EXTR_OVERWRITE);
        }

        if (!$this->error_file_path) {
            $file_running = "$this->root_path/$this->error_file_name";
        } else {
            $file_running = $this->error_file_path;
        }
        if (is_file($file_running)) {
            require $file_running;
        } else {
            echo '<h1>404 Not Found System</h1>';
        }
    }

    /**
     * Set Title
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Get Title
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * 
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * 
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * 
     * @param array $keywords
     */
    public function setKeywords($keywords) {
        $this->keywords = $keywords;
    }

    /**
     * 
     * @return string
     */
    public function getKeywords() {
        if (is_array($this->keywords)) {
            return implode(", ", $this->keywords);
        } else {
            return $this->keywords;
        }
    }

    /**
     * Set more <meta> tags
     * @param array $multi_meta_tags
     */
    public function setMultiMetaTags($multi_meta_tags) {
        $this->meta_tags = array_merge($this->meta_tags, $multi_meta_tags);
    }

    /**
     * Set 1 <meta> tags
     * @param array $meta_tags
     */
    public function setMetaTags($meta_tags) {
        $this->meta_tags[] = $meta_tags;
    }

    /**
     * Clear $meta_tags
     */
    public function clearMetaTags() {
        $this->meta_tags = [];
    }

    /**
     * Get all <meta> tags
     * @return string|boolean
     */
    public function getMetaTags() {
        if ($this->meta_tags) {
            $returns = [];
            $i = 1;
            foreach ($this->meta_tags as $object) {
                $tags = '<meta ';
                foreach ($object as $o_attribute => $o_value) {
                    if (in_array($o_attribute, ['name', 'property', 'http-equiv', 'charset', 'itemprop'])) {
                        $key = $o_attribute . "=" . $o_value;
                        if (isset($object['disable_overwrite']) && $object['disable_overwrite']) {
                            $key .= "|$i";
                        }
                    }
                    if ($o_attribute !== 'disable_overwrite') {
                        $tags .= $o_attribute . '="' . $o_value . '" ';
                    }
                }
                $tags .= '/>';
                $returns[$key] = $tags;
                $i++;
            }
            return implode("\n        ", $returns) . "\n";
        }
        return false;
    }

    /**
     * Set more <link> tags
     * @param array $multi_link_tags
     */
    public function setMultiLinkTags($multi_link_tags) {
        $this->link_tags = array_merge($this->link_tags, $multi_link_tags);
    }

    /**
     * Set 1 <link> tags
     * @param array $link_tags
     */
    public function setLinkTags($link_tags) {
        $this->link_tags[] = $link_tags;
    }

    /**
     * Get all <link> tags
     * @return string|boolean
     */
    public function getLinkTags() {
        if ($this->link_tags) {
            $returns = [];
            foreach ($this->link_tags as $object) {
                $tags = '<link ';
                foreach ($object as $o_attribute => $o_value) {
                    $tags .= $o_attribute . '="' . $o_value . '" ';
                }
                $tags .= '/>';
                $returns[] = $tags;
            }
            return implode("\n        ", $returns) . "\n";
        }
        return false;
    }

    /**
     * Clear $link_tags
     */
    public function clearLinkTags() {
        $this->link_tags = [];
    }

    /**
     * Set more <javascript> tags
     * @param array $multi_javascript_tags
     * @param string $key
     */
    public function setMultiJavascriptTags($multi_javascript_tags, $key = 'default') {
        $this->javascript_tags[$key] = isset($this->javascript_tags[$key]) ? array_merge($this->javascript_tags[$key], $multi_javascript_tags) : $this->javascript_tags[$key] = $multi_javascript_tags;
    }

    /**
     * Set 1 <javascript> tags
     * @param type $javascript_tags
     * @param type $key
     */
    public function setJavascriptTags($javascript_tags, $key = 'default') {
        $this->javascript_tags[$key][] = $javascript_tags;
    }

    /**
     * Get all <javascript> tags
     * @param string $key
     * @return string|boolean
     */
    public function getJavascriptTags($key = 'default') {
        if (isset($this->javascript_tags[$key]) && $this->javascript_tags[$key]) {
            $returns = [];
            foreach ($this->javascript_tags[$key] as $object) {
                $tags = '<script ';
                foreach ($object as $o_attribute => $o_value) {
                    $tags .= $o_attribute . '="' . $o_value . '" ';
                }
                $tags .= '></script>';
                $returns[] = $tags;
            }
            return implode("\n        ", $returns) . "\n";
        }
        return false;
    }

    /**
     * Clear $javascript_tags
     */
    public function clearJavascriptTags() {
        $this->javascript_tags = [];
    }

    /**
     * Set theme_folder, layout path, include path
     * @param string $theme_folder
     */
    public function setThemeFolder($theme_folder) {
        $this->theme_folder = $theme_folder;
        $this->setLayout("$this->root_path/$this->theme_folder/$this->layout_file_name");
        set_include_path("$this->root_path/$this->theme_folder");
    }

    /**
     * 
     * @param string $error_file_path
     */
    public function setErrorFilepath($error_file_path) {
        $this->error_file_path = $error_file_path;
    }

    /**
     * 
     * @param string $error_file_name
     */
    public function setErrorFilename($error_file_name) {
        $this->error_file_name = $error_file_name;
    }

    /**
     * 
     * @param string $layout_file_path
     */
    public function setLayout($layout_file_path) {
        $this->layout_file_path = $layout_file_path;
    }

    /**
     * 
     * @param array $assign
     */
    public function setAssign($assign) {
        $this->assign = array_merge($this->assign, $assign);
    }

    /**
     * 
     * @param string $root_path
     */
    public function setRootPath($root_path) {
        $this->root_path = $root_path;
    }

    /**
     * 
     * @param string $file_path
     */
    public function setFilePath($file_path) {
        $this->file_path = $file_path;
    }

    /**
     * 
     * @param string $template_path
     */
    public function setTemplatePath($template_path) {
        $this->template_path = $template_path;
    }

    /**
     * 
     * @param string $module
     */
    public function setModule($module) {
        $this->module = $module;
    }

    /**
     * 
     * @param string $controller
     */
    public function setControler($controller) {
        $this->controller = $controller;
    }

    /**
     * 
     * @param string $action
     */
    public function setAction($action) {
        $this->action = $action;
    }

}
