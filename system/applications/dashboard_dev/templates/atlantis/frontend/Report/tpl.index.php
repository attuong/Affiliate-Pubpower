<div class="page-inner" id="report-manager">
    <div class="page-header">
        <h4 class="page-title">Reports</h4>
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
                <a href="#">Reports</a>
            </li>
        </ul>
    </div>

    <?php if ($device != 'phone'): ?>
        <style>@media (min-width: 992px) {.content{overflow:inherit !important;}}</style>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-12 no-bd" id="custom-scroll">

            <?php include('index/tpl.filter.php'); ?>

            <div class="card border-transparent">
                <div class="card-header <?= $theme_settings->card_header; ?>">
                    <div class="card-head-row">
                        <div class="card-title text-white">
                            <i class="fas fa-chart-bar mr-2"></i> Reports
                        </div>
                    </div>
                </div>
                <div class="card-body" id="result-ajax-report" style="min-height: 500px;">
                    <div class="row result-highchart"></div>
                    <div class="result-report">
                        <div class="col-md-12 pt-3 <?= $device == 'phone' ? 'p-0' : '' ?>">
                            <div class="<?= $device == 'phone' ? 'table-responsive' : 'table-responsive---scroll' ?>">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Revenue of Websites</th>
                                        <th>Commission (Provisional)</th>
                                        <th style="width: 100px" class="<?= !empty($filters['user']) ? 'd-none' : '' ?>">Filter</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($reports)): ?>
                                        <?php foreach ($reports as $report): ?>
                                            <tr style="letter-spacing: 1px" data-month="<?= $report->month ?>">
                                                <td><?= date('Y/m', strtotime($report->month . '01')) ?></td>
                                                <td>$<?= number_format($report->revenue, 2) ?></td>
                                                <td>$<?= number_format($report->commission, 2) ?></td>
                                                <td class=""><a href="javascript:void(0)" data-toggle="tooltip" title="Filter by Websites" class="filter_websites"><i class="fas fa-globe-americas"></i></a></td>
                                            </tr>
                                            <tr id="filter-<?= $report->month ?>" class="result-filter d-none"><td colspan="100%"></td></tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="100%">No data</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.filter_websites').click(function () {
            var month = $(this).closest('tr').attr('data-month');
            $('#filter-' + month).removeClass('d-none').find('td').html(LoadingCenter());

            $.ajax({
                type: 'GET',
                url: '/ajax/report/filter_website_by_report',
                data: {month: month}
            }).done(function (result) {
                if (result.error) {
                    notifiResult(result.error);
                } else {
                    $('#filter-' + month).html(result);
                }
            })
        })

        $('body').on('click', '.close_filter', function () {
            $(this).closest('tr').find('td').html('').addClass('d-none');
        })
    });
</script>
