<style>
    /*    .table-condensed thead tr:nth-child(2),
        .table-condensed tbody {
            display: none
        }*/
</style>
<div class="row">
    <div class="col-md-12">
        <form id="filters" action="">
            <div class="form-row">
                <div class="form-group col-md-4 col-xl-2">
                    <select class="select2 form-control" name="f_status" data-placeholder="Select By Status" onchange="this.form.submit();" data-allow-clear="true" data-minimum-results-for-search="-1">
                        <option value=""></option>
                        <option value="2" <?= isset($filters['status']) && $filters['status'] == '2' ? 'selected' : '' ?>>Paid</option>
                        <option value="1" <?= isset($filters['status']) && $filters['status'] == '1' ? 'selected' : '' ?>>Pending</option>
                    </select>
                </div>
                <div class="form-group col-md-4 col-xl-2">
                    <div class="input-group">
                        <button type="button" class="daterangepicker-field form-control" data-bind="daterangepicker: dateRange2"><?= !empty($filters['start_month']) && !empty($filters['end_month']) ? date('M Y', strtotime($filters['start_month'] . '01')) . ' - ' . date('M Y', strtotime($filters['end_month'] . '01')) : 'Default' ?></button>
                        <input type="hidden" id="input_start_month" name="f_start_month" value="<?= !empty($filters['start_month']) ? $filters['start_month'] : '' ?>"/>
                        <input type="hidden" id="input_end_month" name="f_end_month" value="<?= !empty($filters['end_month']) ? $filters['end_month'] : '' ?>"/>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
 var time_zone = 'America/New_York';
//    moment.tz.setDefault(time_zone);  
    $(".daterangepicker-field").daterangepicker({
        maxDate: [moment(), 'expanded'],
        minDate: ['2018-11-01', 'expanded'],
        Format: 'MMMM YYYY',
        period: 'month',
        orientation: 'right',
        single: false,
        expanded: true,
        ranges: {
            'Default': [moment().subtract('month').startOf('month'), moment().subtract(9, 'month').endOf('month')],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'All Time': 'all-time', // [minDate, maxDate]
        },
    }, function (startDate, endDate, period) {
        $(this).text(startDate.format('L') + ' – ' + endDate.format('L'));
        $('#input_start_month').val(startDate.format('YMM'));
        $('#input_end_month').val(endDate.format('YMM'));
        $('#filters').submit();
    });

</script>
