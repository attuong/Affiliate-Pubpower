<?php

use core\Model;

/**
 * Description of UserModel
 *
 * @author TungDT
 */
class UserModel extends Model {

    protected $cookie_name = 'ldsre_sadmacc';

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->setDefaultTable(TABLE_USER);
    }

    /**
     * 
     * @param object $user
     * @return boolean|object
     */
    public function extend($user) {
        if (!$user) {
            return false;
        }
        $user->fullname = $user->first_name . " " . $user->last_name;
        return $user;
    }

    /**
     * Validate inputs for update account
     * @param array $inputs
     * @return boolean|array
     */
    public function validate_edit_account($inputs) {
        if (isset($inputs['password']) && isset($inputs['retype_password']) && $inputs['password'] != $inputs['retype_password']) {
            return error('Password does not match the retype password');
        }
        return true;
    }

    /**
     * Update infomation for user account
     * @param array $inputs
     * @param int $user_id
     * @return boolean|array
     */
    public function update_account($inputs, $user_id) {
        if (!empty($inputs['first_name'])) {
            $inputs['first_name'] = trim(strip_tags($inputs['first_name']));
        }
        if (!empty($inputs['last_name'])) {
            $inputs['last_name'] = trim(strip_tags($inputs['last_name']));
        }
        if (isset($inputs['retype_password'])) {
            unset($inputs['retype_password']);
        }
        if (isset($inputs['password']) && !$inputs['password']) {
            unset($inputs['password']);
        }
        if (isset($inputs['password']) && $inputs['password']) {
            $inputs['password'] = password_salt($inputs['password']);
        }

        $query = ['id' => $user_id];
        $update = $this->update($query, $inputs);
        return $update;
    }

    /**
     * Validate input for create new account
     * @param array $inputs
     * @return boolean|array
     */
    public function validate_create_account($inputs) {
        $error = false;
        if (!isset($inputs['email']) || !$inputs['email']) {
            $error = 'Email is a required field';
        }
        if (!$error && (!isset($inputs['password']) || !trim(strip_tags($inputs['password'])))) {
            $error = 'Password is a required field';
        }
        if (!$error && (!isset($inputs['last_name']) || !trim(strip_tags($inputs['last_name'])))) {
            $error = 'Last name is a required field';
        }
        if (!$error && (!isset($inputs['first_name']) || !trim(strip_tags($inputs['first_name'])))) {
            $error = 'Last name is a required field';
        }
        if (!$error && (!isset($inputs['retype_password']) || !$inputs['retype_password'])) {
            $error = 'Retype Password is a required field';
        }
        if (!$error && $inputs['password'] != $inputs['retype_password']) {
            $error = 'Password does not match the retype password';
        }
        if ($error) {
            return error($error);
        }
        return true;
    }

    /**
     * Check email for use for create new account
     * @param array $inputs
     * @return boolean
     */
    public function is_use($inputs) {
        if (isset($inputs['email']) && $inputs['email']) {
            $user = $this->getByEmail($inputs['email']);
            return $user;
        }
        return false;
    }

    /**
     * Create new account
     * @param array $inputs
     * @return boolean|array
     */
    public function create_account($inputs) {
        if ($inputs) {
            if (isset($inputs['retype_password'])) {
                unset($inputs['retype_password']);
            }
            $inputs['password'] = password_salt($inputs['password']);
            $new_user_id = $this->insert($inputs);
            if (!isset($new_user_id['error'])) {

                // Update login token
                $login_token = $this->build_login_token($new_user_id);
                $update_token = $this->update(['id' => $new_user_id], ['login_token' => $login_token]);
                if (isset($update_token['error'])) {
                    return $update_token;
                }
            }
            return $new_user_id;
        }
        return false;
    }

    /**
     * Get User by Email
     * @param string $email
     * @param array $field
     * @return object|array
     */
    public function getByEmail($email, $field = []) {
        $query = [
            'email' => $email
        ];
        $user = $this->findOne($query, $field);
        return $user;
    }

    /**
     * Render login token for account
     * @param int $user_id
     * @return string
     */
    public function build_login_token($user_id) {
        return md5(md5(md5($user_id . "vli_best")));
    }

    /**
     * Validate input login
     * @param type $inputs
     * @return boolean
     */
    public function validate_login($inputs) {
        $error = false;
        if (!isset($inputs['email']) || !$inputs['email']) {
            $error = "Please enter email!";
        }
        if (!$error && (!isset($inputs['password']) || !$inputs['password'])) {
            $error = "Please enter password!";
        }
        if ($error) {
            return error($error);
        }
        return TRUE;
    }

    /**
     * Handle login
     * @param type $inputs
     * @return type
     */
    public function login($inputs) {
        $query = [
            'email' => $inputs['email'],
            'password' => password_salt($inputs['password']),
            'permission' => [
                '$in' => ['affiliate', 'admin']
            ],
            'status' => 'active'
        ];
        $field = ['id', 'email', 'login_token'];
        $user = $this->findOne($query, $field);
        if (!$user) {
            return error('Your email or password is incorrect');
        }
        $this->set_login($user, $inputs['remember']);
        ActivityModel::getInstance()->setUser($user)->setLog_condition($query)->User_login();
        return TRUE;
    }

    /**
     * 
     * @param object $user
     * @param boolean $remember
     * @param number $expire
     * @return boolean
     */
    public function set_login($user, $remember, $expire = 15) {
        if (!$user) {
            return false;
        }
        $_expire = (time() + 3600 * 24 * $expire); // 15day
        if (!$remember) {
            $_SESSION[$this->cookie_name] = $user->login_token;
            $return = true;
        } else {
            $return = setcookie($this->cookie_name, $user->login_token, $_expire, '/', '.' . DOMAIN);
        }
        setcookie("_admin", json_encode(['hello', $user->id]), $_expire, '/', '.' . DOMAIN_MASTER);
        return $return;
    }

    /**
     * Get user login info
     * @return boolean
     */
    public function get_user_login() {
        if (isset($_SESSION[$this->cookie_name]) && $_SESSION[$this->cookie_name]) {
            $token = $_SESSION[$this->cookie_name];
        } elseif (isset($_COOKIE[$this->cookie_name]) && $_COOKIE[$this->cookie_name]) {
            $token = $_COOKIE[$this->cookie_name];
        }
        if (!isset($token)) {
            return false;
        }
        $user = $this->extend($this->getByToken($token));
        if (!$user) {
            return false;
        }
        return $user;
    }

    /**
     * Get user from token
     * @param type $token
     * @param type $field
     * @return type
     */
    public function getByToken($token, $field = [], $user_profile = true) {
        if (!$token) {
            return false;
        }
        $query = ['login_token' => $token, 'status' => 'active'];
        $user = $this->db->setTable(TABLE_USER)->setQuery($query)->setField($field)->setLimit(1)->findOne();
        return $user;
    }

    /**
     * Clear cookie, session
     * @return boolean
     */
    public function logout() {
        if (isset($_SESSION[$this->cookie_name]) && $_SESSION[$this->cookie_name]) {
            unset($_SESSION[$this->cookie_name]);
        }
        if (isset($_COOKIE[$this->cookie_name]) && $_COOKIE[$this->cookie_name]) {
            setcookie($this->cookie_name, "", time() - 3600, '/', '.' . DOMAIN);
        }

        setcookie("_admin", "", time() - 3600, '/', '.' . DOMAIN);
        return true;
    }

    public function auto_login($user) {
        if (isset($_SESSION[$this->cookie_name]) && $_SESSION[$this->cookie_name]) {
            unset($_SESSION[$this->cookie_name]);
        }
        if (isset($_COOKIE[$this->cookie_name]) && $_COOKIE[$this->cookie_name]) {
            setcookie($this->cookie_name, "", time() - 3600, '/', '.' . DOMAIN);
        }

        $this->set_login($user, false);
        return true;
    }

    public function getAllUser($field = [], $order = ['id' => 'DESC']) {
        $query = [
            'deleted_at' => 0,
            'status' => 'active',
            'permission' => 'publisher'
        ];
        $Users = $this->find($query, $field, $order);
        return $Users;
    }

    public function getBillingByUser() {
        $user = $this->get_user_login();
        $query = ['user_id' => $user->id];
        $billing = $this->setTable(TABLE_BILLING_USER)->findOne($query);
        return $billing;
    }

    public function validate_billing($inputs) {
        if (empty($inputs['payment_method'])) {
            return 'Payment Method is a required field';
        }
        return true;
    }

    public function update_billing($inputs) {

        $payment_method = isset($inputs['payment_method']) ? trim(strip_tags($inputs['payment_method'])) : '';
        $payment_email = isset($inputs['payment_email']) ? trim(strip_tags($inputs['payment_email'])) : '';
        $crypto_currency = isset($inputs['crypto_currency']) ? trim(strip_tags($inputs['crypto_currency'])) : '';
        $wallet_id = isset($inputs['wallet_id']) ? trim(strip_tags($inputs['wallet_id'])) : NULL;
        $beneficiary_name = isset($inputs['beneficiary_name']) ? trim(strip_tags($inputs['beneficiary_name'])) : '';
        $bank_name = isset($inputs['bank_name']) ? trim(strip_tags($inputs['bank_name'])) : '';
        $bank_address = isset($inputs['bank_address']) ? trim(strip_tags($inputs['bank_address'])) : '';
        $bank_account_number = isset($inputs['bank_account_number']) ? trim(strip_tags($inputs['bank_account_number'])) : 0;
        $bank_routing_number = isset($inputs['bank_routing_number']) ? trim(strip_tags($inputs['bank_routing_number'])) : 0;
        $swift_code = isset($inputs['swift_code']) ? trim(strip_tags($inputs['swift_code'])) : 0;
        $bank_iban_number = isset($inputs['bank_iban_number']) ? trim(strip_tags($inputs['bank_iban_number'])) : 0;

        if (!$payment_method)
            return NULL;

        if (in_array($payment_method, ['paypal', 'payoneer'])) {
            $beneficiary_name = $bank_name = $bank_address = $crypto_currency = '';
            $bank_account_number = $bank_routing_number = $bank_iban_number = $swift_code = 0;
            $wallet_id = NULL;

            if (!$payment_email) {
                return ['error' => 'Payment Email is required'];
            }
        }

        if ($payment_method == 'bank') {
            $payment_email = $crypto_currency = '';
            $wallet_id = NULL;

            if (!$beneficiary_name)
                return ['error' => 'Beneficiary Name is required'];
            if (!$bank_name)
                return ['error' => 'Bank Name Name is required'];
            if (!$bank_address)
                return ['error' => 'Bank Address Name is required'];
            if (!$bank_account_number)
                return ['error' => 'Bank Acount Number Name is required'];
        }

        if ($payment_method == 'coin') {
            $payment_email = '';
            $beneficiary_name = $bank_name = $bank_address = '';
            $bank_account_number = $bank_routing_number = $bank_iban_number = $swift_code = 0;

            if (!$crypto_currency)
                return ['error' => 'Coin is required'];
            if (!$wallet_id)
                return ['error' => 'Wallets is required'];
        }

        $user = $this->get_user_login();

        $data = [
            'payment_method' => $payment_method,
            'payment_email' => $payment_email,
            'crypto_currency' => $crypto_currency,
            'wallet_id' => $wallet_id,
            'user_id' => $user->id,
            'beneficiary_name' => $beneficiary_name,
            'bank_name' => $bank_name,
            'bank_address' => $bank_address,
            'bank_account_number' => $bank_account_number,
            'bank_routing_number' => $bank_routing_number,
            'swift_code' => $swift_code,
            'bank_iban_number' => $bank_iban_number,
        ];

        $billing_user = $this->getBillingByUser();
        if ($billing_user) {
            $data['last_change'] = time();
            $this->setTable(TABLE_BILLING_USER)->update(['id' => $billing_user->id], $data);
            $messages['success'] = 'Update success!';
        } else {
            $data['create_time'] = time();
            $this->setTable(TABLE_BILLING_USER)->insert($data);
            $messages['success'] = 'Update success!';
        }
        return $messages;
    }

    public function getAllPublisherForAffiliate() {
        $user = $this->get_user_login();
        $query = ['presenter' => $user->id];
        $publishers = $this->find($query);
        return $publishers;
    }

    public function getAllPresenters($field = [], $order = ['id' => 'DESC']) {
        $Users = $this->find(['permission' => 'affiliate'], $field, $order);
        return $Users;
    }

    public function getAffLevel($level, $order = ['amount' => 'DESC']) {
        $query = ['level' => $level];
        $aff_level = $this->setTable(TABLE_AFF_LEVEL)->find($query, [], $order);
        return $aff_level;
    }

    public function register($inputs) {
        $first_name = htmlspecialchars(trim(strip_tags($inputs['first_name'])));
        $last_name = htmlspecialchars(trim(strip_tags($inputs['last_name'])));
        $email = htmlspecialchars(trim(strip_tags($inputs['email'])));
        $password = htmlspecialchars(trim(strip_tags($inputs['password'])));

        $captcha_token = trim($inputs['captcha_token']);
        $data = [
            'secret' => "6Lec_q4UAAAAAJwbYaRLwz2tTfAbHRiRFOobgDt6",
            'response' => $captcha_token
        ];
        $result_captcha = json_decode(_curlPost("https://www.google.com/recaptcha/api/siteverify", $data), true);
        if (!$result_captcha['success'] || $result_captcha['score'] < 0.5) {

            return ['error' => 'Google\'s captcha evaluates your visitor as a bot'];
        } else {

            if (strlen($password) < 8) {
                return ['error' => 'Password must have at least 8 characters.'];
            } else {
                $password = password_salt($password);

                $user = $this->getByEmail($email);
                if ($user) {
                    return ['error' => 'Email already exists'];
                } else {
                    $insert = [
                        'password' => $password,
                        'email' => $email,
                        'permission' => 'affiliate',
                        'status' => 'active',
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'net' => 45,
                        'aff_level' => 1,
                        'create_time' => time()
                    ];
                    $user = $this->insert($insert);
                    if ($user) {
                        $this->Update(['id' => $user], ['login_token' => $this->build_login_token($user)]);
                    }

                    $data = [
                        'admin_id' => $user,
                        'email' => $email,
                        'create_at' => time()
                    ];
                    $_SESSION['_admin'] = $data;

                    header('location:' . ROOTDOMAIN);
                    exit();
                }
            }
        }
    }

}
