<?php if (isset($success_message)): ?>
    <script type="text/javascript">
        $(document).ready(function () {
            swal("Good job!", "<?= $success_message && $success_message != TRUE ? $success_message : 'Success!'; ?>", {
                icon: "success",
                buttons: {
                    confirm: {
                        className: 'btn btn-success'
                    }
                }
            });
        });
    </script>
<?php endif; ?>
<?php if (isset($error_message)): ?>
    <script type="text/javascript">
        $(document).ready(function () {
            swal("Error!!!", "<?= $error_message; ?>", {
                icon: "error",
                buttons: {
                    confirm: {
                        className: 'btn btn-danger'
                    }
                }
            });
        });
    </script>
    <style>
        .swal-text {
            color: #F27374;
        }
    </style>
<?php endif; ?>
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Users</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="<?= ROOTDOMAIN; ?>"> <i class="flaticon-home"></i> </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="<?= URL_USER; ?>">Users</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <span>Billing</span>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header <?= $theme_settings->card_header; ?>">
                    <div class="card-title text-white pull-left"><i class="fas fa-user-edit mr-2"></i> Billing</div>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8" style="margin: 0 auto">

                                <div class="example-wrap">
                                    <form method="POST" action="">
                                        <div class="form-group">
                                            <label class="form-control-label">Email Address to receive Monthly Billing</label>
                                            <input type="text" class="form-control" name="email" value="<?= isset($user->email) ? $user->email : '' ?>" readonly style="cursor: not-allowed;">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label">Payment Method
                                                <sup class="text-danger">*</sup></label>
                                            <select class="form-control black payment_method" name="method">
                                                <option value="bank" <?= isset($billing->method) && $billing->method == "bank" ? 'selected' : '' ?>>Wire Transfer</option>
                                                <option value="payoneer" <?= isset($billing->method) && $billing->method == "payoneer" ? 'selected' : '' ?>>Payoneer</option>
                                            </select>
                                        </div>
                                        <div class="form-group S-PPPO" <?= isset($billing->method) && !in_array($billing->method, ['paypal', 'payoneer']) ? 'style="display: none"' : '' ?>>
                                            <label class="form-control-label">Payment Email
                                                <sup class="text-danger">*</sup></label>
                                            <input type="text" class="form-control" value="<?= $billing->method == "payoneer" ? $billing->payoneer_email : $billing->paypal_email ?>" name="payment_email">
                                        </div>
                                        <div class="form-group S-COIN p-0" <?= isset($billing->method) && $billing->method != 'coin' ? 'style="display: none"' : '' ?>>
                                            <div class="form-group">
                                                <label class="form-control-label">Crypto Currency
                                                    <sup class="text-danger">*</sup></label>
                                                <select class="form-control crypto_currency" name="crypto_currency">
                                                    <option value="BTC" <?= isset($billing->crypto_currency) && $billing->crypto_currency == "BTC" ? 'selected' : '' ?>>Bitcoin (BTC)</option>
                                                    <option value="BCH" <?= isset($billing->crypto_currency) && $billing->crypto_currency == "BCH" ? 'selected' : '' ?>>Bitcoin Cash (BCH)</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <p class="mb-0 minumum-btc <?= isset($data['crypto_currency']) && $data['crypto_currency'] == "BTC" ? '' : 'd-none' ?>" style="color: red;text-align: center;">Minimum Payment Threshold:
                                                    <span id="minumum">1000</span> USD</p>
                                                <p class="mb-0 minumum-bch <?= isset($data['crypto_currency']) && $data['crypto_currency'] == "BCH" ? '' : 'd-none' ?>" style="color: red;text-align: center;">Minimum Payment Threshold:
                                                    <span id="minumum">100</span> USD</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label">Wallet ID
                                                    <sup class="text-danger">*</sup></label>
                                                <input type="text" class="form-control" name="wallet_id" value="<?= isset($billing->wallet_id) ? $billing->wallet_id : '' ?>">
                                            </div>
                                        </div>
                                        <div class="form-group S-BANK p-0" <?= isset($billing->method) && $billing->method != 'bank' ? 'style="display: none"' : '' ?>>
                                            <div class="form-group">
                                                <div style="color: red;text-align: center;">Minimum Payment Threshold:<span id="minumum">1500</span> USD</div>
                                                <p style="color: red;text-align: center;">Please choose other payment methods if your revenue has not reached $1500 for the best support.</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label">Beneficiary Name
                                                    <sup class="text-danger">*</sup></label>
                                                <input type="text" class="form-control" name="beneficiary_name" value="<?= isset($billing->beneficiary_name) ? $billing->beneficiary_name : '' ?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label">Bank Name
                                                    <sup class="text-danger">*</sup></label>
                                                <input type="text" class="form-control" name="bank_name" value="<?= isset($billing->bank_name) ? $billing->bank_name : '' ?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label">Bank Address
                                                    <sup class="text-danger">*</sup></label>
                                                <textarea type="text" rows="2" class="form-control" name="bank_address"><?= isset($billing->bank_address) ? $billing->bank_address : '' ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label">Bank Account Number
                                                    <sup class="text-danger">*</sup></label>
                                                <input type="number" class="form-control" name="bank_account_number" value="<?= isset($billing->bank_account_number) && $billing->bank_account_number ? $billing->bank_account_number : '' ?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label">Bank Routing Number</label>
                                                <input type="text" class="form-control" name="bank_routing_number" value="<?= isset($billing->bank_routing_number) && $billing->bank_routing_number ? $billing->bank_routing_number : '' ?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label">Swift Code</label>
                                                <input type="text" class="form-control" name="swift_code" value="<?= isset($billing->swift_code) && $billing->swift_code ? $billing->swift_code : '' ?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-control-label">Bank Iban Number</label>
                                                <input type="text" class="form-control" name="bank_iban_number" value="<?= isset($billing->bank_iban_number) && $billing->bank_iban_number ? $billing->bank_iban_number : '' ?>">
                                            </div>
                                        </div>
                                        <button name="submit" type="submit" class="btn btn-info ladda-button waves-effect waves-classic">
                                            <span class="ladda-label"><i class="icon md-check" aria-hidden="true"></i>Update</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function ($) {
        var payment_method = $(".payment_method");
        ChangeMethod();
        payment_method.change(ChangeMethod);

        function ChangeMethod() {
            var payment = $(".payment_method").val();
            if (payment == 'bank') {
                $(".S-BANK").show();
                $(".S-PPPO").hide();
                $(".S-COIN").hide();
            }
            if (payment == 'currency') {
                $(".S-COIN").show();
                $(".S-BANK").hide();
                $(".S-PPPO").hide();
            }
            if (payment == 'paypal' || payment == 'payoneer') {
                $(".S-PPPO").show();
                $(".S-BANK").hide();
                $(".S-COIN").hide();
            }
        }

        $(".crypto_currency").change(ChangeCurrency)
        ChangeCurrency()

        function ChangeCurrency() {

            var Method = $(".payment_method").val();
            if (Method != 'currency') {
                $('.minumum-btc').addClass('d-none');
                $('.minumum-bch').addClass('d-none');
                return
            }

            var currency = $(".crypto_currency").val();
            switch (currency) {
                case 'BTC':
                    $('.minumum-btc').removeClass('d-none');
                    $('.minumum-bch').addClass('d-none');
                    break;
                case 'BCH':
                    $('.minumum-btc').addClass('d-none');
                    $('.minumum-bch').removeClass('d-none');
                    break;
                default:
                    $('.minumum-btc').addClass('d-none');
                    $('.minumum-bch').addClass('d-none');
                    break;
            }
        }
    })
</script>