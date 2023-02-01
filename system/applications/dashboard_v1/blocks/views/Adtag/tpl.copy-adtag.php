<?php if ($tag->type_id == 0): ?>

    <div class="adsbyvli" data-ad-slot="<?= $tag->adSlot ?>"></div>
    <script>(vitag.Init = window.vitag.Init || []).push(function () {
            viAPItag.display("<?= $tag->adSlot ?>")
        })</script>

<?php endif; ?>

<?php if ($tag->type_id == 1): ?>

    <div class="adsbyvli" data-ad-slot="<?= $tag->adSlot ?>"></div>
    <script>
        vitag.videoConfig = {
            width: <?= $tag->width ?>,
            height: <?= $tag->height ?>,
            loadingText: "Loading advertisement..",
            complete: function () {},
            error: function () {}
        };
        (vitag.Init = window.vitag.Init || []).push(function () {
            viAPItag.startPreRoll('<?= $tag->adSlot ?>');
        });
    </script>

<?php endif; ?>

<?php if ($tag->type_id == 4): ?>

    <div class="adsbyvli" data-ad-slot="<?= $tag->adSlot ?>" data-width="300" data-height="250"></div>
    <script>
        vitag.videoDiscoverConfig = {
            random: true,
            wgTitle: "FEATURED VIDEOS",
            wgTitleColor: "#eee",
            contentClick: "inline",
            titleColor: "#fff",
            titleHoverColor: "#ff4f02",
            background: "",
            selectedBackground: "#333",
        };
        (vitag.Init = window.vitag.Init || []).push(function () {
            viAPItag.initInstreamBanner("<?= $tag->adSlot ?>")
        });
    </script>

<?php endif; ?>


<?php if ($tag->type_id == 2): ?>

    <script>
        vitag.outStreamConfig = {
            type: "slider",
            position: "right"
        };
    </script>

<?php endif; ?>

