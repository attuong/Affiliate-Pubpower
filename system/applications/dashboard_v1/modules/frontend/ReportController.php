<?php

namespace controller\frontend;

use core\Controller;

/**
 * Description of ReportController
 *
 * @author PC
 */
class ReportController extends Controller {

    /** @var \ReportModel $ReportModel */
    protected $ReportModel;

    /** @var \DomainModel $DomainModel */
    protected $DomainModel;

    /** @var \AdtagModel $AdtagModel */
    protected $AdtagModel;

    /** @var \UserModel $UserModel */
    protected $UserModel;

    /** @var \BidderModel $BidderModel */
    protected $BidderModel;

    public function __construct() {
        parent::__construct();
        $this->ReportModel = $this->load->model('Report');
        $this->DomainModel = $this->load->model('Domain');
        $this->AdtagModel = $this->load->model('Adtag');
        $this->BidderModel = $this->load->model('Bidder');
        $this->UserModel = $this->load->model('User');
    }

    public function indexAction() {
        $inputs = $this->input->filters();
        $filters = $this->ReportModel->handle_input_for_filter($inputs);
        $this->assign['filters'] = $filters;

        //get reports 
        $reports = $this->ReportModel->list_by_filters($filters);
        $this->assign['reports'] = $reports;

        $this->setTitle("Reports - VLI");
        $this->load->view($this->assign);
    }

    public function filter_website_by_reportAction() {
        $month = $this->input->get('month');
        if (!$month) {
            $this->template->page_not_found($this->assign);
            exit();
        }

        $report_websites = $this->ReportModel->get_report_website_by_month($month);
        $this->assign['report_websites'] = $report_websites;

        $this->template->setLayout(LAYOUT_EMPTY);
        $this->load->view($this->assign);
    }
}
