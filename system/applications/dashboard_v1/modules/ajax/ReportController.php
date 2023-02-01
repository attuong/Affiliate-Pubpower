<?php

namespace controller\ajax;

use core\Controller;

/**
 * Description of ReportController
 *
 * @author Tuan
 */
class ReportController extends Controller {

    /** @var \ReportModel $ReportModel */
    protected $ReportModel;

    /** @var \DomainModel $DomainModel */
    protected $DomainModel;

    /** @var \AdtagModel $AdtagModel */
    protected $AdtagModel;

    public function __construct() {
        parent::__construct();
        $this->ReportModel = $this->load->model('Report');
        $this->DomainModel = $this->load->model('Domain');
        $this->AdtagModel = $this->load->model('Adtag');
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
