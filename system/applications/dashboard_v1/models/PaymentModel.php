<?php

use core\Model;

/**
 * Description of PaymentModel
 *
 * @author Tuan
 */
class PaymentModel extends Model {

    public function __construct($config = []) {
        parent::__construct($config);
        $this->setDefaultTable(TABLE_PAYMENT);
    }

//    public function handle_inputs_for_filters($inputs) {
//        return $inputs;
//    }

    public function list_by_filters($filters = [], $order = FALSE, $limit = FALSE) {
        $query = [];
        if (!empty($filters['status'])) {
            $query['status'] = $filters['status'];
        }
        if (!empty($filters['start_month']) && !empty($filters['end_month'])) {
            $query['$and'] = [
                ['end_date' => ['>=' => $filters['start_month'] . '01']],
                ['end_date' => ['<=' => date('Ymt', strtotime($filters['end_month'] . '01'))]]
            ];
        }
        $user = UserModel::getInstance()->get_user_login();
        if (!$user) {
            return [];
        }

        $query['user_id'] = $user->id;
        $payments = $this->find($query, [], $order, $limit);
        return $payments;
    }

    public function extend($payments) {
        if ($payments) {
            $field = ['id', 'first_name', 'last_name', 'email'];
            foreach ($payments as $key => $payment) {
                $user = UserModel::getInstance()->getById($payment->user_id, $field);
                $billing = UserModel::getInstance()->getBillingByUser($user->id);
                $payments[$key]->user = $user;
                $payments[$key]->billing = $billing;
                $payments[$key]->billing_html = $this->render_billing_method($billing);
                $payments[$key]->payer = UserModel::getInstance()->getById($payment->admin_id, ['id', 'email']);
            }
        }
        return $payments;
    }

    public function render_billing_method($billing) {
        $billing_html = '';
        if ($billing) {
            switch ($billing->payment_method) {
                case "bank":
                    $billing_html = '<b>Wire Transfer</b> <a href="javascript:void(0)" class="text-info show-billing-waretransfer" beneficiary_name="' . $billing->beneficiary_name . '" bank_name="' . $billing->bank_name . '" bank_address="' . $billing->bank_address . '" bank_account_number="' . $billing->bank_account_number . '" bank_routing_number="' . $billing->bank_routing_number . '" bank_iban_number="' . $billing->bank_iban_number . '" swift_code="' . $billing->swift_code . '" data-toggle="modal" data-target="#ModalBillingWireTransfer">(show)</a>';
                    break;

                case "paypal":
                    $billing_html = '<b class="text-primary">' . ucfirst($billing->payment_method) . ':</b> ' . $billing->payment_email;
                    break;

                case "payoneer":
                    $billing_html = '<b class="text-success">' . ucfirst($billing->payment_method) . ':</b> ' . $billing->payment_email;
                    break;

                case "currency":
                    switch ($billing->crypto_currency) {
                        case "BTC":
                            $billing_html = '<b class="text-danger">Bitcoin</b><div>' . $billing->wallet_id . '</div>';
                            break;
                        case "ETH":
                            $billing_html = '<b class="text-danger">Ethereum</b><div>' . $billing->wallet_id . '</div>';
                            break;
                        case "BCH":
                            $billing_html = '<b class="text-danger">Bitcoin Cash</b><div>' . $billing->wallet_id . '</div>';
                            break;
                        case "USDT":
                            $billing_html = '<b class="text-danger">Tether</b><div>' . $billing->wallet_id . '</div>';
                            break;
                        case "XRP":
                            $billing_html = '<b class="text-danger">XRP</b><div>' . $billing->wallet_id . '</div>';
                            break;
                        case "LTC":
                            $billing_html = '<b class="text-danger">Litecoin</b><div>' . $billing->wallet_id . '</div>';
                            break;
                    }
            }
        }
        return $billing_html;
    }

}
