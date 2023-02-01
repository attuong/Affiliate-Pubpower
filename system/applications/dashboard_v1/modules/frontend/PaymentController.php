<?php

namespace controller\frontend;

use core\Controller;

/**
 * Description of PaymentController
 *
 * @author Tuan
 */
class PaymentController extends Controller {

    /** @var \PaymentModel $PaymentModel */
    protected $PaymentModel;

    public function __construct() {
        parent::__construct();
        $this->PaymentModel = $this->load->model('Payment');
    }

    public function indexAction() {
        $filters = $this->input->filters();
        $this->assign['filters'] = $filters;

        $payments = $this->PaymentModel->list_by_filters($filters, ['month' => 'DESC', 'user_id' => 'DESC'], $this->pagination->limit_string);
        $this->assign['payments'] = $payments;

        $total = $this->PaymentModel->getTotal();
        $this->pagination->setTotal($total);

        $this->setTitle('Payment -  aff.valueimpression.com');
        $this->load->view($this->assign);
    }

}
