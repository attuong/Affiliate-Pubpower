<?php

use core\Blocks;

/**
 * Description of ReportBlock
 *
 * @author Tuan
 */
class ReportBlock extends Blocks {

    public function __construct() {
        parent::__construct();
    }

    public function export_report_template($reports = false) {
        $this->assign['reports'] = array_reverse($reports);
        $html = $this->render("Report/tpl.export-report.php", $this->assign);
        return $html;
    }

}
