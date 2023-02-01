<html>
    <head>
        <title>Valueimpression - Ads</title>
        <meta charset="UTF-8">
        <!-- Valueimpression Header Tags -->
        <script type="text/javascript" src="<?= render_new_js_tag($data['domain_id']) ?>" defer="" async=""></script>
        <script>
            var vitag = vitag || {};
            <?php if(isset($data['tag_video'])){ ?>
<!-- preroll config -->
            vitag.videoConfig = {
                width: 640,
                height: 480,
                loadingText: "Loading advertisement...",
                complete: function () {
                    console.log("ad complete");
                },
                error: function () {
                    console.log("ad error");
                }
            };
            <!-- end preroll config -->
            <?php } ?>

            <?php if(isset($data['tag_outstream_slider'])){ ?>
<!--  outstream config -->
            vitag.outStreamConfig = {
                type: "slider",
                position: "right"
            };
            <!--  end outstream config -->
            <?php } ?>

            <?php if(isset($data['tag_instream_banner'])){ ?>
<!--  video discover config  -->
            vitag.videoDiscoverConfig = {
                random: true,
                contentClick: "inline",
                titleColor: "#fff",
                titleHoverColor: "#ff4f02",
                background: "",
                selectedBackground: "#333",
            };
            <?php } ?>
</script>
        <!-- End Valueimpression Header Tags -->
    </head>

    <body style="height: 2000px;padding-top: 100px;">
        <div style="width:920px;margin:0 auto">
            <?php if (isset($data['160x600'])) { ?>
<div style="float:left;margin-right:30px;width:160px;height:600px;">
                <button onclick="(vitag.Init = window.vitag.Init || []).push(function() { viAPItag.refresh('<?= $data['160x600']->adSlot ?>'); });">Refresh Ads</button><br/>
                <!-- 160x600 Banner -->
                <div class="adsbyvli" data-ad-slot="<?= $data['160x600']->adSlot ?>"></div>
                <script>(vitag.Init = window.vitag.Init || []).push(function () {viAPItag.display('<?= $data['160x600']->adSlot ?>')})</script>
                <!-- End 160x600 Banner -->
            </div>
            <?php } ?>

            <div style="float:left;width:700px;">
                <?php if (isset($data['728x90'])) { ?>
    <button onclick="(vitag.Init = window.vitag.Init || []).push(function() { viAPItag.refresh('<?= $data['728x90']->adSlot ?>'); });">Refresh Ads</button><br/>
                <!-- 728x90 Banner -->
                <div class="adsbyvli" data-ad-slot='<?= $data['728x90']->adSlot ?>'></div>
                <script>(vitag.Init = window.vitag.Init || []).push(function () {viAPItag.display('<?= $data['728x90']->adSlot ?>')})</script>
                <!-- End 728x90 Banner -->
                <br/>
            <?php } ?>

            <?php if (isset($data['300x250'])) { ?>
    <button onclick="(vitag.Init = window.vitag.Init || []).push(function() { viAPItag.refresh('<?= $data['300x250']->adSlot ?>'); });">Refresh Ads</button><br/>
                <!-- 300x250 Banner -->
                <div class="adsbyvli" data-ad-slot='<?= $data['300x250']->adSlot ?>'></div>
                <script>(vitag.Init = window.vitag.Init || []).push(function () {viAPItag.display('<?= $data['300x250']->adSlot ?>')})</script>
                <!-- End 300x250 Banner -->
                <br/>
            <?php } ?>

            <?php if (isset($data['tag_video'])) { ?>
    <!-- preroll video ads -->
                <div style="background: #eee;position: relative;width:640px;">
                    <button onclick="(vitag.Init = window.vitag.Init || []).push(function() { viAPItag.startPreRoll('<?= $data['tag_video']->adSlot ?>'); });">Start Preroll</button>
                    <br/>
                    <div style="position: relative;width:640px; height: 480px;">
                        <div class="adsbyvli" data-ad-slot='<?= $data['tag_video']->adSlot ?>'></div>
                    </div>
                </div>
                <!-- End preroll video ads -->
                <br/>
            <?php } ?>

            <?php if (isset($data['tag_instream_banner'])) { ?>
    <!-- Video discover -->
                <div>
                    <div class="adsbyvli" data-ad-slot="<?= $data['tag_instream_banner']->adSlot ?>" data-width="300" data-height="600"></div>
                    <script> (vitag.Init = window.vitag.Init || []).push(function () {viAPItag.initInstreamBanner("<?= $data['tag_instream_banner']->adSlot ?>")}); </script>
                </div>
            <?php } ?>
</div>
            <div style="clear: both"></div>
        </div>
    </body>
</html>
