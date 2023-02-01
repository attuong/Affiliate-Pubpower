
alert success/error
<?php if (!empty($message['error'])) { ?>
    <script>
        $(document).ready(function () {
            notifiError('<?= $message['error'] ?>');
        })
    </script>
<?php } ?>
<?php if (!empty($message['success'])) { ?>
    <script>
        $(document).ready(function () {
            notifiSuccess('<?= $message['success'] ?>');
        })
    </script>
<?php } ?>




<!-- Date and time range -->
<div class="form-group">
    <div class="input-group">
        <button type="button" class="btn btn-light btn-flat" id="date_filter">
            <span>
                <i class="fa fa-calendar mr-2"></i> 
                This Month: Apr 1, 2019 - Apr 30, 2019
            </span>
            <i class="fa fa-caret-down"></i>
        </button>
        <input type="hidden" id="input_start_date" name="start_date" value=""/>
        <input type="hidden" id="input_end_date" name="end_date" value=""/>
        <input type="hidden" id="input_label" name="label" value="This Month: Apr 1, 2019 - Apr 30, 2019"/>
    </div>
</div>
<!--/.form group-->

<script type="text/javascript">
    var time_zone = 'America/New_York';
    moment.tz.setDefault(time_zone);
    //        var defaultStartDate = moment();
    //        var defaultEndDate = moment();
    var defaultStartDate = moment().subtract(30, 'days');
    var defaultEndDate = moment().subtract(1, 'days');
    $('#date_filter').daterangepicker({
        ranges: {
            'Lifetime': [moment.unix(1533701256), moment()],
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 days': [moment().subtract(7, 'days'), moment().subtract(1, 'days')],
            'Last 14 days': [moment().subtract(14, 'days'), moment().subtract(1, 'days')],
            'Last 30 days': [moment().subtract(30, 'days'), moment().subtract(1, 'days')],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        autoUpdateInput: false,
        alwaysShowCalendars: true,
        autoApply: false,
        startDate: defaultStartDate,
        endDate: defaultEndDate
    }, function (start, end, label) {
        sm(start, end, label);
    });
    function sm(start, end, label) {
        $('#input_start_date').val(start.format('YYYYMMDD'));
        $('#input_end_date').val(end.format('YYYYMMDD'));
        $('#input_label').val(label);
        $('#filters').submit();
    }


    // config Sweet Alert 
    $('#alert_demo_7').click(function (e) {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            buttons: {
                confirm: {
                    text: 'Yes, delete it!',
                    className: 'btn btn-success'
                },
                cancel: {
                    visible: true,
                    className: 'btn btn-danger'
                }
            }
        }).then((ok) => {
            if (ok) {
                swal({
                    title: 'Deleted!',
                    text: 'Your file has been deleted.',
                    type: 'success',
                    buttons: {
                        confirm: {
                            className: 'btn btn-success'
                        }
                    }
                });
            } else {
                swal.close();
            }
        });
    });




</script>


<!--loading ...-->
<div id="loadding" class="text-center">
    <img class="avatar-sm" src="/themes/atlantis/assets/img/loadding.svg" alt="loadding"/>
</div>