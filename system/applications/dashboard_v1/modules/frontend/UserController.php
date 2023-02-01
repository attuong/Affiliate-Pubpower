<?php

namespace controller\frontend;

use core\Controller;

/**
 * Description of UserController
 *
 * @author PC
 */
class UserController extends Controller {

    /** @var \UserModel $UserModel */
    protected $UserModel;

    /** @var \AdtagModel $AdtagModel */
    protected $AdtagModel;

    /** @var \ActivityModel $ActivityModel */
    protected $ActivityModel;

    public function __construct() {
        parent::__construct();
        $this->ActivityModel = $this->load->model('Activity');
        $this->UserModel = $this->load->model('User');
    }

    public function settingAction() {
        $user = $this->UserModel->get_user_login();
        $this->assign['user'] = $user;

        // Submit Update
        if ($this->input->post()) {
            $inputs = $this->input->getAllPost();
            $this->assign['inputs'] = $inputs;
            $validate = $this->UserModel->validate_edit_account($inputs);
            if (!isset($validate['error'])) {

                $update = $this->UserModel->update_account($inputs, $user->id);
                if (!isset($update['error'])) {
                    $this->ActivityModel->User_update(['id' => $user->id], $user, $inputs);
                    set_message("update-account-{$user->id}", 'Account has been updated!');
                    redirect(URL_USER);
                } else {
                    $this->assign['error_message'] = $update['error'];
                }
            } else {
                $this->assign['error_message'] = $validate['error'];
            }
        }

        // Get message for alert
        $create_message_success = get_message("new-account-$user->id");
        $this->assign['success_message'] = $create_message_success;
        if (!$create_message_success) {
            $update_message_success = get_message("update-account-$user->id");
            $this->assign['success_message'] = $update_message_success;
        }

        $this->setTitle("Edit user: \"{$user->email}\"");
        $this->load->view($this->assign);
    }

    public function billingAction() {
        $user = $this->UserModel->get_user_login();
        $this->assign['user'] = $user;

        // Submit Update
        if ($this->input->post()) {
            $inputs = $this->input->getAllPost();
            $this->assign['inputs'] = $inputs;
            $validate = $this->UserModel->validate_billing($inputs);
            if (!isset($validate['error'])) {

                $update = $this->UserModel->update_billing($inputs, $user->id);
                if (!isset($update['error'])) {
                    $this->assign['success_message'] = true; //'Update billing success!';
                } else {
                    $this->assign['error_message'] = $update['error'];
                }
            } else {
                $this->assign['error_message'] = $validate['error'];
            }
        }

        //get Billing for publisher 
        $billing = $this->UserModel->getBillingByUser($user->id);
        $this->assign['billing'] = $billing;

        $this->setTitle("Billing: \"{$user->email}\"");
        $this->load->view($this->assign);
    }

}
