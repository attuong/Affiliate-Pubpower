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
class Pagination {

    public $start;
    public $skip;
    public $limit;
    public $page = 1;
    public $limit_string;
    public $url;
    public $total = 0;
    public $neighbors = 2;

    /** @var $instance Pagination */
    public static $instance = null;

    public function __construct($limit = 10) {
        $this->setLimit($limit);
        $this->setUrlAuto();
    }

    public static function getInstance($limit = 10) {
        if (null === static::$instance) {
            static::$instance = new static($limit);
        }
        return static::$instance;
    }

    public function setLimit($limit, $page = 0) {
        $this->limit = $limit;
        if ($page <= 0) {
            $this->page = isset($_GET['page']) && $_GET['page'] ? (int) $_GET['page'] : 1;
        } else {
            $this->page = $page;
        }
        $this->skip = ($this->page - 1) * $this->limit;
        $this->start = $this->page * $this->limit - $this->limit;
        $this->limit_string = "{$this->start},{$this->limit}";
        return $this;
    }

    public function setTotal($total) {
        $this->total = $total;
        return $this;
    }

    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    public function setUrlAuto() {
        $url = current_url();
        $parse_url = parse_url($url);
        $_url = $parse_url['scheme'] . "://" . $parse_url["host"] . $parse_url["path"];
        $query_url = urldecode(parse_url($url, PHP_URL_QUERY));
        if (!$query_url) {
            $_url .= "?";
        } else {
            $explode = explode("&", $query_url);
            $params = [];
            if ($explode) {
                foreach ($explode as $vl) {
                    if (strpos($vl, "page=") !== false) {
                        continue;
                    } else {
                        if ($vl) {
                            $param = explode("=", $vl);
                            $is_array = stripos($param[0], '[]');
                            if ($is_array !== false) {
                                $_param = str_replace('[]', '', $param[0]);
                                $params[$_param][] = $param[1];
                            } else {
                                $params[$param[0]] = $param[1];
                            }
                        }
                    }
                }
            }
            if ($params) {
                $_query = http_build_query($params);
                $query = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $_query);
//                $_url .= "?" . urldecode($query) . "&";
                $_url .= "?" . $query . "&";
            } else {
                $_url .= "?";
            }
        }
        $this->url = $_url;
        return $this;
    }

    public function setNeighbors($neighbors) {
        return $this->neighbors = $neighbors;
    }

    public function isShow() {
        if ($this->total > $this->limit) {
            return true;
        }
        return false;
    }

    public function show_with_ul($css_class = 'pagination') {
        if ($this->isShow()) {
            echo '<ul class="' . $css_class . ' pg-default">';
            echo $this->show_with_li();
            echo '</ul>';
        }
    }

    public function show_with_li() {
        if ($this->start >= $this->total) {
            $this->start = max(0, $this->total - (($this->total % $this->limit) == 0 ? $this->limit : ($this->total % $this->limit)));
        } else {
            $this->start = max(0, (int) $this->start - ((int) $this->start % (int) $this->limit));
        }
        $base_link = '<li class="page-item"><a class="page-link" href="' . strtr($this->url, array('%' => '%%')) . 'page=%d' . '">%s</a></li>';
        $out[] = $this->start == 0 ? '' : sprintf($base_link, $this->start / $this->limit, '<i class="fa fa-angle-left"></i>');
        if ($this->start > $this->limit * $this->neighbors) {
            $out[] = sprintf($base_link, 1, '1');
        }
        if ($this->start > $this->limit * ($this->neighbors + 1)) {
            $out[] = '<li class="page-item"><span class="page-link" style="font-weight: bold;">...</span></li>';
        }
        for ($nCont = $this->neighbors; $nCont >= 1; $nCont--) {
            if ($this->start >= $this->limit * $nCont) {
                $tmpStart = $this->start - $this->limit * $nCont;
                $out[] = sprintf($base_link, $tmpStart / $this->limit + 1, $tmpStart / $this->limit + 1);
            }
        }
        $out[] = '<li class="page-item active"><a class="page-link currentpage">' . ($this->start / $this->limit + 1) . '</a></li>';
        $tmpMaxPages = (int) (($this->total - 1) / $this->limit) * $this->limit;
        for ($nCont = 1; $nCont <= $this->neighbors; $nCont++) {
            if ($this->start + $this->limit * $nCont <= $tmpMaxPages) {
                $tmpStart = $this->start + $this->limit * $nCont;
                $out[] = sprintf($base_link, $tmpStart / $this->limit + 1, $tmpStart / $this->limit + 1);
            }
        }
        if ($this->start + $this->limit * ($this->neighbors + 1) < $tmpMaxPages) {
            $out[] = '<li class="page-item"><span class="page-link" style="font-weight: bold;">...</span></li>';
        }
        if ($this->start + $this->limit * $this->neighbors < $tmpMaxPages) {
            $out[] = sprintf($base_link, $tmpMaxPages / $this->limit + 1, $tmpMaxPages / $this->limit + 1);
        }
        if ($this->start + $this->limit < $this->total) {
            $display_page = ($this->start + $this->limit) > $this->total ? $this->total : ($this->start / $this->limit + 2);
            $out[] = sprintf($base_link, $display_page, '<i class="fa fa-angle-right"></i>');
        }
        return implode(' ', $out);
    }

    public function show_review_pagination_ul($rate = null, $css_class = 'pagination pagination-sm') {
        if ($this->isShow()) {
            echo '<ul class="' . $css_class . '">';
            echo $this->show_review_pagination_li($rate);
            echo '</ul>';
        }
    }

    public function show_review_pagination_li($rate = null) {
        if ($this->start >= $this->total) {
            $this->start = max(0, $this->total - (($this->total % $this->limit) == 0 ? $this->limit : ($this->total % $this->limit)));
        } else {
            $this->start = max(0, (int) $this->start - ((int) $this->start % (int) $this->limit));
        }
        if ($rate != null && in_array($rate, [1, 2, 3, 4, 5])) {
            $base_link = '<li><a onclick="loadReview(%d, ' . $rate . ');">%s</a></li>';
        } else {
            $base_link = '<li><a onclick="loadReview(%d);">%s</a></li>';
        }
        $out[] = $this->start == 0 ? '' : sprintf($base_link, $this->start / $this->limit, '<i class="fa fa-angle-left"></i>');
        if ($this->start > $this->limit * $this->neighbors) {
            $out[] = sprintf($base_link, 1, '1');
        }
        if ($this->start > $this->limit * ($this->neighbors + 1)) {
            $out[] = '<li><span style="font-weight: bold;">...</span></li>';
        }
        for ($nCont = $this->neighbors; $nCont >= 1; $nCont--) {
            if ($this->start >= $this->limit * $nCont) {
                $tmpStart = $this->start - $this->limit * $nCont;
                $out[] = sprintf($base_link, $tmpStart / $this->limit + 1, $tmpStart / $this->limit + 1);
            }
        }
        $out[] = '<li class="active"><a class="currentpage">' . ($this->start / $this->limit + 1) . '</a></li>';
        $tmpMaxPages = (int) (($this->total - 1) / $this->limit) * $this->limit;
        for ($nCont = 1; $nCont <= $this->neighbors; $nCont++) {
            if ($this->start + $this->limit * $nCont <= $tmpMaxPages) {
                $tmpStart = $this->start + $this->limit * $nCont;
                $out[] = sprintf($base_link, $tmpStart / $this->limit + 1, $tmpStart / $this->limit + 1);
            }
        }
        if ($this->start + $this->limit * ($this->neighbors + 1) < $tmpMaxPages) {
            $out[] = '<li><span style="font-weight: bold;">...</span></li>';
        }
        if ($this->start + $this->limit * $this->neighbors < $tmpMaxPages) {
            $out[] = sprintf($base_link, $tmpMaxPages / $this->limit + 1, $tmpMaxPages / $this->limit + 1);
        }
        if ($this->start + $this->limit < $this->total) {
            $display_page = ($this->start + $this->limit) > $this->total ? $this->total : ($this->start / $this->limit + 2);
            $out[] = sprintf($base_link, $display_page, '<i class="fa fa-angle-right"></i>');
        }
        return implode(' ', $out);
    }

    public function show_counter() {
        echo 'Page <strong>' . $this->page . '</strong> of <strong>' . ceil($this->total / $this->limit) . "</strong> entries";
    }

}
