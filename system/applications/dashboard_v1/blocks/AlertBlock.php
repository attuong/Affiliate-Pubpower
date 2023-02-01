<?php

use core\Blocks;

/**
 * Description of MessageBlock
 *
 * @author TungDT
 */
class AlertBlock extends Blocks {

    static protected $warning_message;
    static protected $success_message;
    static protected $error_message;

    public function __construct() {
        parent::__construct();
    }

    public static function SetSuccessMessage($success_message) {
        self::$success_message = $success_message;
    }

    public static function SetWarningMessage($warning_message) {
        self::$warning_message = $warning_message;
    }

    public static function SetErrorMessage($error_message) {
        self::$error_message = $error_message;
    }

    public static function show() {
        $assign = [
            'success_message' => self::$success_message,
            'warning_message' => self::$warning_message,
            'error_message' => self::$error_message
        ];
        $html = self::render('Message/tpl.show_message.php', $assign);
        return $html;
    }

    public static function showWithModal() {
        $assign = [
            'success_message' => self::$success_message,
            'warning_message' => self::$warning_message,
            'error_message' => self::$error_message
        ];
        $html = self::render('Message/tpl.show_message_with_modal.php', $assign);
        return $html;
    }

}
