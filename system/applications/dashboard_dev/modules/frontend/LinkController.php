<?php

namespace controller\frontend;

use core\Controller;

/**
 * Description of LinkController
 *
 * @author Tuan
 */
class LinkController extends Controller {

    /** @var \PaymentModel $PaymentModel */
    protected $PaymentModel;

    /** @var \UserModel UserModel */
    protected $UserModel;

    public function __construct() {
        parent::__construct();
        $this->PaymentModel = $this->load->model('Payment');
        $this->UserModel = $this->load->model('User');
    }

    public function indexAction() {
        $user = $this->UserModel->get_user_login();
        $this->assign['user'] = $user;

        $this->setTitle('Link Affiliate -  aff.valueimpression.com');
        $this->load->view($this->assign);
    }

}
