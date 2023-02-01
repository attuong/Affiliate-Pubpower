<?php

use core\Blocks;

/**
 * Description of FrontendBlock
 *
 * @author THUYHQ
 */
class FrontendBlock extends Blocks {

    /** @var \CategoryModel $CategoryModel */
    protected $CategoryModel;

    /** @var \StoryModel $StoryModel */
    protected $StoryModel;

    public function __construct() {
        parent::__construct();
    }

    public function ProductItem($product) {
        $this->assign['product'] = $product;
        $this->assign['add_ref_current_url'] = FALSE;
        $this->assign['use_bootstrap_layout'] = TRUE;
        $this->assign['use_owl_wrapper'] = FALSE;
        return $this->render('Frontend/tpl.product_item.php', $this->assign);
    }

    public function ProductItemForOwlCarouselInNews($product) {
        $this->assign['product'] = $product;
        $this->assign['add_ref_current_url'] = TRUE;
        $this->assign['use_bootstrap_layout'] = FALSE;
        $this->assign['use_owl_wrapper'] = FALSE;
        return $this->render('Frontend/tpl.product_item.php', $this->assign);
    }

    public function ProductItemForOwlCarousel($product) {
        $this->assign['product'] = $product;
        $this->assign['add_ref_current_url'] = FALSE;
        $this->assign['use_bootstrap_layout'] = FALSE;
        $this->assign['use_owl_wrapper'] = TRUE;
        return $this->render('Frontend/tpl.product_item.php', $this->assign);
    }

    public function footerNewProducts() {
        $condition['category_id'] = $this->input->get('footerNewProducts_category_id');
        $condition['product_id'] = $this->input->get('footerNewProducts_product_id');
        $footer_new_products = ProductModel::getInstance()->footerNewProducts($condition);
        $this->assign['footer_new_products'] = $footer_new_products;
        return $this->render('Frontend/tpl.footer_new_products.php', $this->assign);
    }

}
