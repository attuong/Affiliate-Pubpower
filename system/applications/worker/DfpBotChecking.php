<?php

require realpath(dirname(__FILE__)) . '/System.php';

use core\Model;

class dfpBotChecking extends Model {

    public function __construct($config = []) {
        parent::__construct($config);

        $lastReport = $this->setTable(TABLE_DFP_REPORT)->findOne(
                false, ['create_time', 'type'], ['id' => 'DESC']
        );
        if (!$lastReport) {
            return;
        }

        $lastTime = (time() - $lastReport->create_time) / 60;
        $message = "[Bot] {$lastReport->type}: cứu em anh ơi!!!";
        if ($lastTime >= 20) {
            $this->sendMessage(
                    json_encode(['text' => $message])
            );
        }
    }

    public function sendMessage($message) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://hooks.slack.com/services/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");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($message))
        );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_exec($ch);
        curl_close($ch);
    }

}

new dfpBotChecking;
