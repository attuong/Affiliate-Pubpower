<td colspan="100%">
    <div class="card border-transparent filter-box">
        <div class="card-header">
            <div class="card-head-row justify-content-between">
                <div class="card-title">
                    <i class="fa fa-filter"></i> &nbsp;Filter by Publisher
                </div>
                <a href="javascript:void(0)" class="pull-right close_filter"><strong><i class="fa fa-times"></i></strong></a>
            </div>
        </div>
        <div class="maxwidth-450-overflow-y">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table height45">
                        <thead>
                            <tr>
                                <th>Publisher</th>
                                <th>Revenue</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($report_publishers) && $report_publishers): ?>
                                <?php foreach ($report_publishers as $report): ?>
                                    <tr class="report-item" data-pub="<?= $report->email ?>">
                                        <td>
                                            <div><?= $report->first_name . ' ' . $report->last_name ?></div>
                                            <div><?= $report->email ?></div>

                                        </td>
                                        <td>$<?= number_format($report->revenue, 2) ?></td>
                                        <td>&nbsp;</td>
                                    </tr>
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
</td>
