<?php

use core\Blocks;

class AdtagBlock extends Blocks {

    public function __construct() {
        parent::__construct();
    }

    public function render_copy_adtag($tag) {
        if ($tag) {
            $this->assign['tag'] = $tag;
            $html = $this->render("Adtag/tpl.copy-adtag.php", $this->assign);
            $html = clearAllLine($html);
            echo $html;
        }
    }

    public function render_adtag_html($data) {
        if ($data) {
            $this->assign['data'] = $data;
            $html = $this->render("Adtag/tpl.down-adtag-html.php", $this->assign);
            return $html;
        }
    }

    public function render_adtag_txt($domain, $adtags, $outstream_slider = '') {
        if ($adtags && $domain) {
            $this->assign['domain'] = $domain;
            $this->assign['adtags'] = $adtags;
            $this->assign['outstream_slider'] = $outstream_slider;
            $html = $this->render("Adtag/tpl.down-adtag-txt.php", $this->assign);
            return $html;
        }
    }

}
