<?php

$version = '2.53';
//$version = time();
$classCalling->theme_url = $assign['theme_url'] = $theme_url = ROOTDOMAIN . '/themes/' . $theme_folder;

// Set default css on header for all page
$classCalling->setCSS([
//    $theme_url . '/assets/css/bootstrap.min.css?v=' . $version,
    $theme_url . '/assets/bootstrap431/css/bootstrap.min.css?v=' . $version,
]);

// Set default JS on header for all page
$classCalling->setJavascript([
    $theme_url . '/assets/js/plugin/webfont/webfont.min.js?v=' . $version,
    $theme_url . '/assets/js/core/jquery.3.2.1.min.js?v=' . $version,
    $theme_url . '/assets/js/core/popper.min.js?v=' . $version,
//    $theme_url . '/assets/js/core/bootstrap.min.js?v' . $version,
    $theme_url . '/assets/bootstrap431/js/bootstrap.min.js?v' . $version,
    // <!-- Datepicker for Bootstrap -->
    $theme_url . '/assets/js/plugin/moment/min/moment.min.js?v=' . $version,
    $theme_url . '/assets/js/plugin/moment/min/moment-timezone-with-data.min.js?v=' . $version,
//    $theme_url . '/assets/js/plugin/bootstrap-daterangepicker/daterangepicker.js?v=' . $version,
        ], 'header'
);

$classCalling->setJavascript([
    // <!-- jQuery UI -->
    $theme_url . '/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js?v=' . $version,
    $theme_url . '/assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js?v=' . $version,
    // <!-- jQuery Scrollbar -->
    $theme_url . '/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js?v=' . $version,
        ], 'footer'
);

switch ($URLSchemes) {

    case 'frontend/Domain/adtag' :
    case 'frontend/Domain/bidder' :
    case 'frontend/Domain/config' :
        $classCalling->setJavascript([
                ], 'footer'
        );
        break;

    case 'frontend/Home/index':
        $classCalling->setJavascript([
            // <!-- Chart JS -->
//            $theme_url . '/assets/js/plugin/chart.js/chart.min.js?v=' . $version,
            // <!-- jQuery Sparkline -->
//            $theme_url . '/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js?v=' . $version,
            // <!-- Chart Circle -->
//            $theme_url . '/assets/js/plugin/chart-circle/circles.min.js?v=' . $version,
            // <!-- Datatables -->
            $theme_url . '/assets/js/plugin/datatables/datatables.min.js?v=' . $version,
            // <!-- jQuery Vector Maps -->
            $theme_url . '/assets/js/plugin/jqvmap/jquery.vmap.min.js?v=' . $version,
            $theme_url . '/assets/js/plugin/jqvmap/maps/jquery.vmap.world.js?v=' . $version,
            // <!-- Atlantis DEMO methods, don't include it in your project! -->
            $theme_url . '/assets/js/setting-demo.js?v=' . $version,
//            $theme_url . '/assets/js/demo.js?v=' . $version,
                ], 'footer'
        );
        $classCalling->setJavascript([
            // <!-- Chart Circle -->
            $theme_url . '/assets/js/plugin/chart-circle/circles.min.js?v=' . $version,
            // <!-- Chart JS -->
            $theme_url . '/assets/js/plugin/chart.js/chart.min.js?v=' . $version,
            // <!-- jQuery Sparkline -->
            $theme_url . '/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js?v=' . $version,
            $theme_url . '/assets/js/plugin/highcharts/highcharts.js?v=' . $version,
            $theme_url . '/assets/js/plugin/highcharts/exporting.js?v=' . $version,
            $theme_url . '/assets/js/plugin/highcharts/export-data.js?v=' . $version,
            $theme_url . '/assets/js/plugin/highcharts/series-label.js?v=' . $version,
                ], 'header'
        );
        break;

    case 'frontend/Report/index':
        $classCalling->setJavascript([
            $theme_url . '/assets/js/plugin/highcharts/highcharts.js?v=' . $version,
            $theme_url . '/assets/js/plugin/highcharts/exporting.js?v=' . $version,
            $theme_url . '/assets/js/plugin/highcharts/export-data.js?v=' . $version,
            $theme_url . '/assets/js/plugin/highcharts/series-label.js?v=' . $version,
            $theme_url . '/assets/js/plugin/daterangepicker-0.1.0/dist/knockout-min.js?v=' . $version,
            $theme_url . '/assets/js/plugin/daterangepicker-0.1.0/dist/moment.min.js?v=' . $version,
            $theme_url . '/assets/js/plugin/daterangepicker-0.1.0/dist/daterangepicker.min.js?v=' . $version,
                ], 'header'
        );

        $classCalling->setCSS([
            $theme_url . '/assets/js/plugin/daterangepicker-0.1.0/dist/daterangepicker.min.css?v=' . $version,
                ], 'header'
        );

    case 'frontend/Payment/index':
        $classCalling->setJavascript([
            $theme_url . '/assets/js/plugin/daterangepicker-0.1.0/dist/knockout-min.js?v=' . $version,
            $theme_url . '/assets/js/plugin/daterangepicker-0.1.0/dist/moment.min.js?v=' . $version,
            $theme_url . '/assets/js/plugin/daterangepicker-0.1.0/dist/daterangepicker.min.js?v=' . $version,
                ], 'header'
        );

        $classCalling->setCSS([
            $theme_url . '/assets/js/plugin/daterangepicker-0.1.0/dist/daterangepicker.min.css?v=' . $version,
                ], 'header'
        );
}
// Set default css on header for all page
$classCalling->setCSS([
    $theme_url . '/assets/js/plugin/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css?v=' . $version,
//    $theme_url . '/assets/js/plugin/bootstrap-daterangepicker/daterangepicker.css?v=' . $version,
//    $theme_url . '/assets/js/plugin/select2/dist/css/select2.min.css?v=' . $version,
//    $theme_url . '/assets/js/plugin/select2/dist/css/select2-bootstrap.min.css?v=' . $version,
    $theme_url . '/assets/css/atlantis.min.css?v=' . $version,
    $theme_url . '/assets/css/custom.css?v=' . $version,
        //    $theme_url . '/assets/css/demo.css?v=' . $version,
]);

// Set default JS on Bottom Footer for all page
$classCalling->setJavascript([
    // <!-- Jquery-Clock -->
    $theme_url . '/assets/js/plugin/jquery-clock/jqClock.min.js?v=' . $version,
    // <!-- Select 2 -->
    $theme_url . '/assets/js/plugin/select2/dist/js/select2.full.min.js?v=' . $version,
    // <!-- sweetalert -->
    $theme_url . '/assets/js/plugin/sweetalert/sweetalert.min.js?v=' . $version,
    // <!-- Bootstrap Notify -->
    $theme_url . '/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js?v=' . $version,
    // <!-- bootstrap-toggle -->
    $theme_url . '/assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js?v=' . $version,
    // <!-- bootstrap-filestyle -->
    $theme_url . '/assets/js/plugin/bootstrap-filestyle/src/bootstrap-filestyle.min.js?v=' . $version,
    // <!-- Atlantis JS -->
    $theme_url . '/assets/js/atlantis.min.js?v=' . $version,
    $theme_url . '/assets/js/custom.js?v=' . $version,
    $theme_url . '/assets/js/aisearch.js?v=' . $version,
        ], 'footer'
);

