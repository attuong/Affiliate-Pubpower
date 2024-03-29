<div class="row">
    <div class="col-md-12">
        <form action="" method="GET" id="filters">
            <div class="form-row">
                <div class="form-group col-xl-2">
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
        console.log( startDate.format('L') );
        console.log( endDate.format('L') );
        if (startDate.format('L') == endDate.format('L')) {
            $(this).text(startDate.format('L'));
        } else {
            $(this).text(startDate.format('L') + ' – ' + endDate.format('L'));
        }
        $('#input_start_month').val(startDate.format('MM/Y'));
        $('#input_end_month').val(endDate.format('MM/Y'));
        $('#filters').submit();
    });
</script>