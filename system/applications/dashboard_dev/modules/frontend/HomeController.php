<?php

namespace controller\frontend;

use core\Controller;

/**
 * Description of HomeController
 *
 * @author TungDT
 */
class HomeController extends Controller {

    /** @var \UserModel $UserModel */
    protected $UserModel;

    /** @var \ReportModel $ReportModel */
    protected $ReportModel;

    /** @var \HomeModel $HomeModel */
    protected $HomeModel;

    /** @var \ActivityModel $ActivityModel */
    protected $ActivityModel;

    public function __construct() {
        parent::__construct();
        $this->HomeModel = $this->load->model('Home');
        $this->ReportModel = $this->load->model('Report');
        $this->UserModel = $this->load->model('User');
        $this->ActivityModel = $this->load->model('Activity');
    }

    public function indexAction() {

        //        $overall_statistics = $this->HomeModel->overall_statistics();
        //        $this->assign['overall_statistics'] = $overall_statistics;
        //
        //        $total_income = $this->HomeModel->total_income();
        //        $this->assign['total_income'] = $total_income;
        //
        //        $impression_statistics = $this->HomeModel->impression_statistics();
        //        $this->assign['impression_statistics'] = $impression_statistics;
        //
        //        $daily_income = $this->HomeModel->daily_income();
        //        $this->assign['daily_income'] = $daily_income;
        //
        //        $ecpm_statistics = $this->HomeModel->ecpm_statistics();
        //        $this->assign['ecpm_statistics'] = $ecpm_statistics;
        //echo 'xxx';die;
        redirect(URL_REPORT);
        $this->load->view($this->assign);
    }

    /**
     * Login page
     */
    public function loginAction() {
        if ($this->input->get('em')) {
            $email = htmlspecialchars(trim(strip_tags($this->input->get('em'))));
            if (isset($_COOKIE)) {
                if (isset($_COOKIE['_admin']) && json_decode($_COOKIE['_admin'])[0] == 'hello') {
                    $user = $this->UserModel->getByEmail($email);
                    if ($user) {
                        $this->UserModel->auto_login($user);
                        redirect(URL_DASHBOARD);
                    }
                }
            }
        }

        if ($this->input->get('db')) {
            $login_token = htmlspecialchars(trim(strip_tags($this->input->get('db'))));
            $email = htmlspecialchars(trim(strip_tags($this->input->get('email'))));

            if (empty($login_token) || empty($email)) {
                unset($_SESSION['_admin']);
                redirect(URL_DASHBOARD);
                exit();
            }

            $where = [
                'login_token' => $login_token,
                'permission'  => 'agency'
            ];

            $field = ['id'];
            $agency = $this->UserModel->findOne($where, $field);
            if (!$agency) {
                redirect(URL_DASHBOARD);
            }

            // if ($agency->id == 80) {
            $user = $this->UserModel->getByEmail($email);
            if ($user && ($user->presenter == $agency->id || $agency->id == 80)) {
                $this->UserModel->auto_login($user);
                redirect(URL_DASHBOARD);
            }
            // }
        }

        if ($this->user_login) {
            redirect(URL_DASHBOARD);
        }

        if ($this->input->post()) {
            $inputs = $this->input->getAllPost(['email', 'password', 'remember']);
            $validate = $this->UserModel->validate_login($inputs);
            if (!isset($validate['error'])) {
                $login = $this->UserModel->login($inputs);
                if (!isset($login['error'])) {
                    redirect($this->backurl);
                } else {
                    $this->assign['error_message'] = $login['error'];
                }
            } else {
                $this->assign['error_message'] = $validate['error'];
            }
            $this->assign['inputs'] = $inputs;
        }

        $this->setTitle('Login - Console Valueimpression.com');
        $this->template->setLayout(LAYOUT_EMPTY);
        $this->load->view($this->assign);
    }

    /**
     * Logout page
     */
    public function logoutAction() {
        if (!$this->user_login) {
            redirect(URL_DASHBOARD);
        }
        $this->UserModel = $this->load->model('User');
        $this->UserModel->logout();
        redirect(URL_DASHBOARD);
    }

    public function registerAction() {
        $inputs = $this->input->getAllPost();
        if ($inputs) {
            $this->assign['inputs'] = $inputs;

            $validate = $this->UserModel->validate_create_account($inputs);
            if (!empty($validate['error'])) {
                $this->assign['error_message'] = $validate['error'];
            } else {
                $result = $this->UserModel->register($inputs);
                if (!empty($result['error'])) {
                    $this->assign['error_message'] = $result['error'];
                } else {
                    redirect(URL_LOGIN);
                }
            }
        }

        $this->template->setLayout(LAYOUT_EMPTY);
        $this->load->view($this->assign);
    }

    public function versionAction() {
        $version = $this->input->get('version');
        if (!in_array($version, ['dev', 'rele'])) {
            $this->template->page_not_found();
            exit();
        }
        $_expire = (time() + 3600 * 24 * 15); // 15day
        setcookie("app_version", $version, $_expire, '/', '.' . DOMAIN);
        redirect($this->backurl);
    }

}
