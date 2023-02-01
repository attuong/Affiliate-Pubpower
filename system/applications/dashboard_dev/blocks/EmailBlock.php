<?php

use core\Blocks;

/**
 * Description of EmailBlock
 *
 * @author THUYHQ
 */
class EmailBlock extends Blocks {

    public function __construct() {
        parent::__construct();
        $this->block_name = 'Email';
    }

    public function confirm_newsletters_subscribe($data) {
        if (!$data) {
            return false;
        }
        return $this->render($this->block_name . '/tpl.confirm_newsletters_subscribe.php', ['data' => $data]);
    }

    public function confirm_survey_completed($data) {
        if (!$data) {
            return false;
        }
        return $this->render($this->block_name . '/tpl.confirm_survey_completed.php', ['data' => $data]);
    }

}
