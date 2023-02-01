<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= $Template->getTitle(); ?></title>
        <?= $Template->getLinkTags(); ?>
        <?= $Template->getMetaTags(); ?>
        <?= $Template->getJavascriptTags(); ?>
        <?= $Template->getJavascriptTags('header'); ?>
        <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW"/>
    </head>
    <body class="login">
        <div class="wrapper wrapper-login">
            <div class="container container-login animated fadeIn">
                <h3 class="text-center">Sign In</h3>
                <?php if (!empty($error_message)): ?>
                <h5 class="text-danger text-center "><i><?= $error_message ?></i></h5>
                <?php endif; ?>
                <div class="login-form">
                    <form method="POST" action="">
                        <div class="form-group form-floating-label">
                            <input id="username" name="email" type="text" class="form-control input-border-bottom" required>
                            <label for="username" class="placeholder">Email</label>
                        </div>
                        <div class="form-group form-floating-label">
                            <input id="password" name="password" type="password" class="form-control input-border-bottom" required>
                            <label for="password" class="placeholder">Password</label>
                            <div class="show-password">
                                <i class="icon-eye"></i>
                            </div>
                        </div>
                        <div class="row form-sub m-0">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="remember" class="custom-control-input" id="rememberme">
                                <label class="custom-control-label" for="rememberme">Remember Me <small class="text-muted">(15 day)</small></label>
                            </div>
                        </div>
                        <div class="form-action mb-3">
                            <button type="submit" class="btn btn-primary btn-rounded btn-login">Sign In</button>
                        </div>
                        <div class="login-account">
                            <span class="msg">Don't have an account yet?</span>
                            <a href="/register" id="show-signup" class="link">Sign Up</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
<script src="http://aff.valueimpression.com/themes/atlantis/assets/js/plugin/sweetalert/sweetalert.min.js" ></script>
<script>

    $(document).ready(function () {
        $('input[name="email"]').focus();
    })
</script>    