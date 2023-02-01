<!--Paste the above code right above the closing </head> of the HTML in your website-->
<script type="text/javascript" src="<?= render_new_js_tag($domain->id) ?>" defer="" async=""></script><script> var vitag = vitag || {}; <?= isset($outstream_slider) && $outstream_slider ? $outstream_slider : '' ?></script>
<!--End Valueimpression Header Tags for <?= $domain->name ?>-->
<?php if (isset($adtags) && $adtags) { ?>
    <?php foreach ($adtags as $key => $tag) { ?>
        <?php if ($tag->type_id == 0): ?>

            <!--Copy and paste the code below to the places on your page where you want ads serving-->
            <?= AdtagBlock::getInstance()->render_copy_adtag($tag); ?>

            <!--End <?= $tag->name ?>-->
        <?php endif; ?>
        <?php if (in_array($tag->type_id, [1, 4])): ?>

            <!--Copy and paste the code below to the places on your page where you want ads serving-->
            <?= AdtagBlock::getInstance()->render_copy_adtag($tag); ?>

            <!--End <?= $tag->name ?>-->
        <?php endif; ?>
    <?php } ?>
<?php } ?>