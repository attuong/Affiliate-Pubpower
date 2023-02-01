<?php

use core\Blocks;

/**
 * Description of BidderBlock
 *
 * @author Tuan
 */
class BidderBlock extends Blocks {

    public function __construct() {
        parent::__construct();
    }

    public function bidder_item($item = false, $bidder = false, $sizes = false, $type = false) {
        $this->assign['item'] = $item;
        $this->assign['bidder'] = $bidder;
        $this->assign['sizes'] = $sizes;
        $this->assign['type'] = $type;
        $this->assign['device'] = $this->device;
        $html = $this->render("Bidder/tpl.bidder-item.php", $this->assign);
        return $html;
    }

    public function bidder_for_domain($domain_id){
        $bidder_domain = BidderModel::getInstance()->list_for_domain_filter($domain_id);
        $this->assign['bidder_domain'] = $bidder_domain;
        $html = $this->render("Bidder/tpl.bidder-for-domain.php", $this->assign);
        return $html;
    }
}
