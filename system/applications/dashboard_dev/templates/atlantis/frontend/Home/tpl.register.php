<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="description" content="Register - Value Impression">
        <meta name="author" content="">
        <meta name="robots" content="nofollow">
        <title>Register - Valueimpression</title>
        <link rel="shortcut icon" type="image/x-icon" href="https://dashboard.valueimpression.com/Favicon.png" />

        <!-- Stylesheets -->
        <link rel="stylesheet" href="https://dashboard.valueimpression.com/static/themeforest/global/css/bootstrap.min.css?v=2">
        <link rel="stylesheet" href="https://dashboard.valueimpression.com/static/themeforest/global/css/bootstrap-extend.min.css?v=2">
        <link rel="stylesheet" href="https://dashboard.valueimpression.com/static/themeforest/assets/css/site.min.css?v=2">

        <!-- Plugins -->
        <link rel="stylesheet" href="https://dashboard.valueimpression.com/static/themeforest/global/vendor/asscrollable/asScrollable.css">
        <link rel="stylesheet" href="https://dashboard.valueimpression.com/static/themeforest/global/vendor/switchery/switchery.css">
        <link rel="stylesheet" href="https://dashboard.valueimpression.com/static/themeforest/global/vendor/intro-js/introjs.css">
        <link rel="stylesheet" href="https://dashboard.valueimpression.com/static/themeforest/global/vendor/slidepanel/slidePanel.css">
        <link rel="stylesheet" href="https://dashboard.valueimpression.com/static/themeforest/global/vendor/flag-icon-css/flag-icon.css">
        <link rel="stylesheet" href="https://dashboard.valueimpression.com/static/themeforest/assets/examples/css/pages/register.css">


        <!-- Fonts -->
        <link rel="stylesheet" href="https://dashboard.valueimpression.com/static/themeforest/global/fonts/web-icons/web-icons.min.css?v=2">
        <link rel="stylesheet" href="https://dashboard.valueimpression.com/static/themeforest/global/fonts/brand-icons/brand-icons.min.css?v=2">
        <link rel='stylesheet' href='//fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>

        <!-- Scripts -->
        <script src="https://www.google.com/recaptcha/api.js?render=6Lec_q4UAAAAAKEGuAB6se6sU9p2sx-Ji82CUwHD"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/vendor/breakpoints/breakpoints.js?v=2"></script>
        <script>
        Breakpoints();
        </script>
    </head>
    <style>
        .form-control {
            color: black;
        }
    </style>
    <body class="animsition page-register layout-full page-dark">
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->


        <!-- Page -->
        <div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
            <div class="page-content vertical-align-middle animation-slide-top animation-duration-1">
                <div class="brand">
                    <img class="brand-img" src="https://dashboard.valueimpression.com/static/image/valueImpression-white.svg" alt="Dashboard.valueimpression.com" style="width: 300px">
                    <p style="color: #ff0000; padding-top: 10px;"><?= !empty($error_message) ? $error_message : '' ?></p>
                </div>
                <form id="formRegister" method="post" role="form">
                    <div class="form-group">
                        <label class="sr-only">First Name</label>
                        <input type="text" class="form-control" id="FirstName" name="first_name" placeholder="First Name" required="" value="<?= !empty($inputs['first_name']) ? $inputs['first_name'] : '' ?>">
                    </div>
                    <div class="form-group">
                        <label class="sr-only">Last Name</label>
                        <input type="text" class="form-control" id="LastName" name="last_name" placeholder="Last Name" required="" value="<?= !empty($inputs['last_name']) ? $inputs['last_name'] : '' ?>">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="inputEmail">Email</label>
                        <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Email" required="" value="<?= !empty($inputs['email']) ? $inputs['email'] : '' ?>">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="inputPassword">Password</label>
                        <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password" required="">
                        <p class="Password" style="display: none; color: #ff0000; padding-top: 10px;">Password must have at least 8 characters.</p>
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="inputPasswordCheck">Retype Password</label>
                        <input type="password" class="form-control" id="inputPasswordCheck" name="retype_password" placeholder="Confirm Password" required="">
                        <p class="checkPass" style="display: none; color: #ff0000; padding-top: 10px;">Passwords do not match.</p>
                    </div>
                    <input id="captcha_token" type="hidden" name="captcha_token"  value="">
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
                </form>
                <p>Have an account already? Please go to <a href="/Login">Sign In</a></p>

                <footer class="page-copyright page-copyright-inverse">
                    <p>©2018 Billions Trading., JSC</p>

                </footer>
            </div>
        </div>
        <!-- End Page -->


        <!-- Core  -->
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/vendor/babel-external-helpers/babel-external-helpers.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/vendor/jquery/jquery.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/vendor/popper-js/umd/popper.min.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/vendor/bootstrap/bootstrap.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/vendor/animsition/animsition.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/vendor/mousewheel/jquery.mousewheel.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/vendor/asscrollbar/jquery-asScrollbar.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/vendor/asscrollable/jquery-asScrollable.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/vendor/ashoverscroll/jquery-asHoverScroll.js?v=2"></script>

        <!-- Plugins -->
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/vendor/screenfull/screenfull.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/vendor/switchery/switchery.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/vendor/intro-js/intro.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/vendor/slidepanel/jquery-slidePanel.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/vendor/jquery-placeholder/jquery.placeholder.js?v=2"></script>

        <!-- Scripts -->
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/js/Component.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/js/Plugin.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/js/Base.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/js/Config.js?v=2"></script>

        <script src="https://dashboard.valueimpression.com/static/themeforest/assets/js/Section/Menubar.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/assets/js/Section/GridMenu.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/assets/js/Section/Sidebar.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/assets/js/Section/PageAside.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/assets/js/Plugin/menu.js?v=2"></script>

        <!-- Config -->
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/js/config/colors.js?v=2"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/assets/js/config/tour.js?v=2"></script>
        <script>Config.set('assets', 'https://dashboard.valueimpression.com/static/themeforest/assets');</script>

        <!-- Page -->
        <script src="https://dashboard.valueimpression.com/static/themeforest/assets/js/Site.js"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/js/Plugin/asscrollable.js"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/js/Plugin/slidepanel.js"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/js/Plugin/switchery.js"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/js/Plugin/jquery-placeholder.js"></script>
        <script src="https://dashboard.valueimpression.com/static/themeforest/global/js/Plugin/animate-list.js"></script>
        <script src="https://aff.valueimpression.com/themes/atlantis/assets/js/plugin/sweetalert/sweetalert.min.js" ></script>


        <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('6Lec_q4UAAAAAKEGuAB6se6sU9p2sx-Ji82CUwHD', {action: 'Register'})
                    .then(function (token) {
                        $('#captcha_token').val(token);
                    });
        });
        </script>
        <script>
            (function (document, window, $) {
                'use strict';

                var Site = window.Site;
                $(document).ready(function () {
                    Site.run();
                });
            })(document, window, jQuery);
        </script>
        <script>
            jQuery(document).ready(function ($) {

                var checkPass = $("#inputPasswordCheck");
                _donetyping(checkPass, 500, function () {
                    var pass = $("#inputPassword").val();
                    var text = checkPass.val();
                    if (text.length > 0) {
                        if (pass != text) {
                            $(".checkPass").show();
                            $("#formRegister").attr('onsubmit', 'return false')
                        }
                        if (pass == text) {
                            $(".checkPass").hide();
                            $("#formRegister").attr('onsubmit', '')
                        }
                    }
                })

                var Password = $("#inputPassword");
                _donetyping(Password, 500, function () {
                    var text = Password.val();
                    if (text.length < 8) {
                        $(".Password").show();
                    } else {
                        $(".Password").hide();
                    }
                })

                function _donetyping($input, doneTypingInterval, callback) {
                    var typingTimer;
                    $input.on('keyup', function () {
                        clearTimeout(typingTimer);
                        typingTimer = setTimeout(callback, doneTypingInterval);
                    });
                    $input.on('keydown', function () {
                        clearTimeout(typingTimer);
                    });
                }
            })
        </script>
    </body>
</html>
