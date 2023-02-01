<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= $Template->getTitle(); ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= $Template->getLinkTags(); ?>
        <?= $Template->getMetaTags(); ?>
        <?= $Template->getJavascriptTags(); ?>
        <?= $Template->getJavascriptTags('header'); ?>
        <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <![endif]-->
        <script type="text/javascript">
            WebFont.load({
                //                google: {"families": ["Lato:300,400,700,900"]},
                google: {
                    "families": ["Roboto:300,400,700,900", "Open Sans:300,400,700,900"]
                },
                custom: {
                    "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands",
                        "simple-line-icons"
                    ],
                    urls: ['<?= $theme_url; ?>/assets/css/fonts.min.css']
                },
                active: function () {
                    sessionStorage.fonts = true;
                }
            });
        </script>
    </head>

    <body data-background-color="bg3">
        <div class="wrapper">
            <?php include 'tpl.__header.php'; ?>
            <?php include 'tpl.__sidebar_left.php'; ?>
            <div class="main-panel">
                <div class="content">
                    <?= $content_template; ?>
                </div>
                <?php include 'tpl.__footer.php'; ?>
            </div>
            <?php // include 'tpl.__custom_template.php'; ?>
        </div>
        <?= $Template->getJavascriptTags('footer'); ?>

        <div id="ai-search" status="off">
            <form method="get" action="/AiSearch/query">
                <input type="text" name="s" value="" placeholder="Search..." />
            </form>
            <div class="ai_note">
                <ul>
                    <li><b>-u</b> : search user by keyword or user ID</li>
                    <li><b>-s</b> : search domain by keyword or domain ID</li>
                    <li>defaut : search report for domain by keyword or domain ID</li>
                </ul>
            </div>
        </div>
    </body>

</html>