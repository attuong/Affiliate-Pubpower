<?php

use core\Model;

/**
 * Description of AdsizeModel
 *
 * @author PC
 */
class WidgetModel extends Model {

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->setDefaultTable(TABLE_WIDGET_CONTENT);
    }

    public function Delete($widget_id) {
        $delete = $this->remove(['id' => $widget_id]);
        return $delete;
    }

    public function validate_save($inputs) {
        $error = false;
        if (!isset($inputs['widget_id']) || ($inputs['widget_id'] != "add_item" && (int) $inputs['widget_id'] < 1)) {
            $error = $inputs['widget_id'] . " - Widget ID required!!";
        } elseif (empty($inputs['title'])) {
            $error = "Title required!!";
        } elseif (empty($inputs['video_mp4']) && empty($inputs['video_ogg'])) {
            $error = "Video MP4 or OGG required!!";
        } elseif (empty($inputs['thumb'])) {
            $error = "Thumb required!!";
        }
        if (!$error) {
            return true;
        } else {
            return error($error);
        }
    }

    public function is_create($inputs) {
        if (!empty($inputs['widget_id']) && (int) $inputs['widget_id'] === 0) {
            return true;
        }
        return false;
    }

    public function create_widget($inputs) {
        if (!$inputs) {
            return error('Data Empty');
        }
        $video_url = json_encode([
            "mp4" => $inputs['video_mp4'],
            "ogg" => $inputs['video_ogg']
        ]);
        $data = [
            'title' => $inputs['title'],
            'des' => $inputs['description'],
            'video_url' => $video_url,
            'thumb' => $inputs['thumb'],
            'link' => $inputs['link'],
            'create_time' => time(),
            'deleted_at' => 0,
            'is_default' => $inputs['is_default']
        ];
        $result = $this->insert($data);
        return $result;
    }

    public function update_widget($inputs) {
        $widget_id = (int) $inputs['widget_id'];
        $video_url = json_encode([
            "mp4" => $inputs['video_mp4'],
            "ogg" => $inputs['video_ogg']
        ]);
        $data = [
            'title' => $inputs['title'],
            'des' => $inputs['description'],
            'video_url' => $video_url,
            'thumb' => $inputs['thumb'],
            'link' => $inputs['link'],
            'create_time' => time(),
            'deleted_at' => 0,
            'is_default' => $inputs['is_default']
        ];
        $query = ['id' => $widget_id];
        $result = $this->update($query, $data);
        return $result;
    }

    public function expanded($widget) {
        if (!$widget) {
            return false;
        }
        if ($widget->video_url) {
            $video_url = json_decode($widget->video_url);
            if ($video_url) {
                $widget->video_url = $video_url;
            }
        }
        if (empty($video_url)) {
            $widget->video_url = (object) ['mp4' => false, 'ogg' => false];
        }
        return $widget;
    }

    public function WidgetByTag($tag_id) {
        $adtag = AdtagModel::getInstance()->getById($tag_id, ['id', 'widget_contents']);
        if (!$adtag) {
            return false;
        }
        $widget_ids = explode(",", $adtag->widget_contents);
        if (!$widget_ids) {
            return false;
        }
        $query = ['id' => ['$in' => $widget_ids]];
        $widgets = $this->find($query, [], ['id' => 'DESC']);
        return $widgets;
    }

    public function DefaultWidgets() {
        $query = ['is_default' => 1, 'title' => ['$ne' => '']];
        $order = ['id' => 'DESC'];
        $default_widgets = $this->find($query, [], $order);
        return $default_widgets;
    }

    public function getContentsByListID($widgetContentsID) {
        if (!$widgetContentsID) {
            return '';
        }
        $field = ['id', 'title', 'des', 'video_url', 'thumb', 'link'];
        $query = ['id' => ['$in' => $widgetContentsID], 'deleted_at' => 0];
        return $this->find($query, $field);
    }

    public function getDefaultWidget() {
        $field = ['id', 'title', 'des', 'video_url', 'thumb', 'link'];
        $query = ['is_default' => 1, 'deleted_at' => 0];
        return $this->find($query, $field);
    }

}
