<?php

use core\Model;

/**
 * Description of UploadModel
 *
 * @author TungDT
 */
class UploadModel extends Model {

    public $dir = NULL;
    public $root_url = NULL;
    public $root_dir = NULL;

    /**
     * date / timestamp / none
     * @var string 
     */
    private $time_struct = "date";

    public function __construct($config = array(), $prefix = false) {
        parent::__construct($config, $prefix);
//        if (ROOTDOMAIN === 're.valueimpression.local') {
//            $this->root_dir = PUBLIC_PATH;
//            $this->dir = "static";
//        } else {
//            $this->root_dir = "/home/value/domains/static.valueimpression.com/public_html";
//            $this->dir = "";
//        }
        $this->root_dir = PUBLIC_PATH;
        $this->dir = "static";
    }

    public function folder_js() {
        return "js" . $this->get_time_struct();
    }

    public function prebid_domain($file_input, $domain) {
        $this->root_dir = "/home/value/domains/static.valueimpression.com/public_html";
        $this->dir = "prebid";
        $file_path = $domain;
        $file_js = $this->upload_js($file_input, $file_path);
        return $file_js;
    }

    public function prebid_default($file_input) {
        $this->root_dir = "/home/value/domains/static.valueimpression.com/public_html";
        $this->dir = "prebid";
        $file_path = "default";
        $file_js = $this->upload_js($file_input, $file_path);
        return $file_js;
    }

    public function upload_js($file_input, $file_path = false) {
        if (!$file_path) {
            $file_path = $this->folder_js();
        }
        $allowed = ["text/javascript"];
        $result = $this->process($file_input, $file_path, $allowed);
        if (isset($result['error'])) {
            return $result;
        }
        return $result;
    }

    /**
     * Get folder upload images
     * @return string
     */
    public function folder_img() {
        return "images";
    }

    public function folder_image() {
        return $this->folder_img() . $this->get_time_struct();
    }

    public function folder_avatar() {
        return $this->folder_img() . "/avatars" . $this->get_time_struct();
    }

    public function thumb_widget($file_input) {
        $this->root_dir = "/home/value/domains/static.valueimpression.com/public_html";
        $this->dir = "widget";
        $file_path = $this->get_time_struct();
        $thumb = $this->image($file_input, $file_path);
        return $thumb;
    }

    public function avatar($file_input) {
        $file_path = $this->folder_avatar();
        return $this->upload_image($file_input, $file_path);
    }

    public function image($file_input, $file_path) {
        $allowed = ['image/*'];
        return $this->process($file_input, $file_path, $allowed);
    }

    public function process($file_input, $file_path, $allowed, $options = []) {
        if (!$file_path) {
            return error("Data file is required!!!");
        }
        $full_path = $this->root_dir . "/" . $this->dir . "/" . $file_path;
        if (!isset($file_path['error']) || $file_path['error'] != 4) {
            $Upload = new library\Upload($file_input);
            if ($Upload->uploaded) {
                $Upload->file_max_size = '2048000';
                $Upload->allowed = $allowed;
                if ($options) {
                    foreach ($options as $key => $value) {
                        $Upload->$key = $value;
                    }
                }
                $Upload->process($full_path);
                if ($Upload->processed) {
                    $return = "/" . $this->dir . "/" . $file_path . "/" . $Upload->file_dst_name;
                    $Upload->clean();
                    sleep(1);
                    return $return;
                } else {
                    return error($Upload->error);
                }
            }
        }
        return false;
    }

    public function get_time_struct() {
        switch ($this->getTime_struct()) {
            case "date":
                $return = date("Y") . "/" . date("m") . "/" . date("d");
                break;

            case "timestamp":
                $return = time();
                break;

            default :
                $return = "";
                break;
        }
        return $return;
    }

    function getTime_struct() {
        return $this->time_struct;
    }

    function setTime_struct($time_struct) {
        $this->time_struct = $time_struct;
    }

}
