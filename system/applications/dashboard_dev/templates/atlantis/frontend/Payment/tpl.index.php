<div class="page-inner" id="payment-request">
    <div class="page-header">
        <h4 class="page-title">Payments</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="<?= ROOTDOMAIN; ?>" title="Dashboard">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Payment </a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12 no-bd">

            <?php include('index/tpl.filter.php'); ?>

            <div class="card border-transparent mt-4">
                <div class="card-header <?= $theme_settings->card_header; ?>">
                    <div class="card-head-row">
                        <div class="card-title text-white">
                            <i class="fas fa-file-invoice-dollar mr-2"></i> Payment 
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Month</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Scheduled Payment</th>
                                        <th scope="col">Paid Date</th>
                                        <th scope="col" style="width: 20%;min-width: 200px;">Note</th>
                                        <th scope="col" style="min-width: 125px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($payments)) { ?>
                                        <?php foreach ($payments as $key => $payment) { ?>
                                            <tr>
                                                <td><?= date('m/Y', strtotime($payment->month . '01')) ?></td>
                                                <td>$<?= number_format($payment->amount, 2) ?></td>                                 
                                                <td><?= $payment->payment_date ? date('d/m/Y', strtotime($payment->payment_date)) : '' ?></td>
                                                <td><?= $payment->payment_time ? date('m/Y', strtotime($payment->payment_time)) : '' ?></td>
                                                <td>
                                                    <span class="short-text"><?= strlen($payment->note) > 20 ? substr($payment->note, 0, 20) . '...<a href="javascript:void(0)" class="view-more text-info">(Show)</a>' : $payment->note ?></span>
                                                    <span class="show-more note-full d-none"><?= $payment->note ?></span>
                                                </td>
                                                <td>
                                                    <span class="<?= $payment->status == 'pending' ? 'text-warning' : 'text-success' ?>">
                                                        <?= $payment->status == 'pending' ? '<i class="fas fa-clock"></i>' : '<i class="fas fa-chevron-circle-down"></i>' ?> <?= $payment->status ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="100%">No data</td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-grey1">
                    <div class="row">
                        <?php if ($pagination->total) : ?>
                            <div class="col-sm-5">
                                <?= $pagination->show_counter(); ?>
                            </div>
                            <div class="col-sm-7">
                                <?= $pagination->show_with_ul(); ?>
                                <style>.pagination{margin: 0;float: right;}</style>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!--End card-body-->
            </div>
            <!--End card-->
        </div>
        <!--End col-md-12-->
    </div>
</div>
<script>
    $(document).ready(function () {
        // view more note
        $('#payment-request').on('click', '.view-more', function () {
            $(this).closest('.short-text').addClass('d-none');
            $(this).closest('td').find('.show-more').removeClass('d-none');
        })

        //insert request
        $('.save-request-payment').click(function () {
            var e = $(this);
            e.prop('disabled', true);
            e.text('Loading..');
            var user = $('.f_user').val();
            var amount = $('.f_amount').val();
            var date = $('.f_date').val();
            var status = $('.f_status').val();
            var note = $('.f_note').val();

            $.ajax({
                type: 'POST',
                url: '/ajax/payment/save_request',
                data: {
                    user: user,
                    amount: amount,
                    date: date,
                    status: status,
                    note: note
                }
            }).done(function (result) {
                notifiResult(result);
                show_request();
                e.prop('disabled', false);
                e.html('<i class="fas fa-check-double mr-2"></i> Save');
            });
        });


        // preview old request by user
        $('.f_user').change(function () {
            show_request();
        });

        function show_request() {
            $('.result-preview').html(LoadingCenter());
            var user = $('.f_user').val();

            $.ajax({
                type: 'POST',
                url: '/ajax/payment/show_request',
                data: {user: user}
            }).done(function (result) {
                if (result.error) {
                    notifiResult(result);
                }
                $('.result-preview').html(result);
            });
        }
    });
</script>