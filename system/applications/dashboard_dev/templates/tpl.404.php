<!doctype html>
<html class="no-js" lang="" >
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>404 Not Found</title>
        <link rel="shortcut icon" href="/themes/_error404/favicon.png" type="image/x-icon" />
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="stylesheet" href="/themes/_error404/css/vendor.css">
        <link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="/themes/_error404/css/main.css">
        <script src="/themes/_error404/js/modernizr.js"></script>
    </head>
    <body>
        <!--[if lt IE 10]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="page_overlay">
            <div class="loader-inner ball-pulse">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="wrap4002 animated fadeIn">
            <div class="row-flex">
                <div class="messge400">
                    <h1><span>404</span> Not Found</h1>
                    <?php if (isset($message) && $message && isset($debug) && $debug): ?>
                        <p>
                            <?= $message; ?>
                        </p>
                    <?php endif ?>
                </div>
            </div>
            <div class=" cloudWrapper">
                <div class="cloud cloud-1"><img src="/themes/_error404/img/cloud-1.png" alt=""></div>
                <div class="cloud cloud-2"><img src="/themes/_error404/img/cloud-2.png" alt=""></div>
                <div class="cloud cloud-4"><img src="/themes/_error404/img/cloud-4.png" alt=""></div>
            </div>
            <div class="waterflow">
                <div class="charecter-5">
                    <div class="charecter-5anim row-flex">
                        <div class="eye5"><img src="/themes/_error404/img/eye-5.gif" alt=""></div>
                        <img src="/themes/_error404/img/charecter-5.png" alt="">
                    </div>
                    <div class="fish"><img src="/themes/_error404/img/fish.png" alt="">
                        <span class="bubble bubble-1"><img src="/themes/_error404/img/buble-1.png" alt=""></span>
                        <span class="bubble bubble-2"><img src="/themes/_error404/img/buble-1.png" alt=""></span>
                        <span class="bubble bubble-3"><img src="/themes/_error404/img/buble-1.png" alt=""></span>
                    </div>
                </div>
            </div>
        </div>
        <script src="/themes/_error404/js/vendor.js"></script>
        <script src="/themes/_error404/js/plugins.js"></script>
        <script src="/themes/_error404/js/main.js"></script>
    </body>
</html>