<?php

namespace controller\frontend;

use core\Controller;

/**
 * Description of DomainController
 *
 * @author Tuan
 */
class DomainController extends Controller {

    /** @var \DomainModel $$DomainModel */
    protected $DomainModel;

    public function __construct() {
        parent::__construct();
        $this->DomainModel = $this->load->model('Domain');
    }

    public function indexAction() {
        $filters = $this->input->filters();
        $this->assign['filters'] = $filters;

        $page = ($this->input->get('page')) ? $this->input->get('page') : 1;
        $this->assign['page'] = $page;

        $domains = $this->DomainModel->list_by_filters($filters, $this->pagination->limit_string);
        $this->assign['domains'] = $domains;
        $total = $this->DomainModel->getTotal();
        $this->pagination->setTotal($total);

        $this->setTitle('Payment -  aff.valueimpression.com');
        $this->load->view($this->assign);
    }

}
