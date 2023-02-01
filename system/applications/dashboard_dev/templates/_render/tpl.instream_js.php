{

<?php foreach ($data as $item) { ?>

    "<?= $item->adSlot ?>" : {
        zid: <?= $item->id ?>, 
        w: <?= $item->size->width ?>, 
        h: <?= $item->size->height ?>,
        floorPrice: <?= $item->floor_price ?>,
        type: "<?= $item->type_id == 4 ? 'inbanner' : 'normal' ?>",
        adunit: 
        {
            code: '<?= $item->adSlot ?>',
            mediaTypes: {
                video: {
                    playerSize: [640,480],
                    context: 'instream',
                    mimes: ['video/mp4', 'video/x-flv', 'video/x-ms-wmv', 'application/vnd.apple.mpegurl', 'application/x-mpegurl', 'video/3gpp', 'video/mpeg', 'video/ogg', 'video/quicktime', 'video/webm', 'video/x-m4v', 'video/ms-asf', 'video/x-msvideo'],
                    protocols: [1, 2, 3, 4, 5, 6],
                    playbackmethod: [<?= $item->type_id == 4 ? 6 : 3 ?>],
                    maxduration: 120,
                    linearity: 1,
                    api: [2]
                }
            },
            bids: <?= $item->bidder_group != '' ? $item->bidder_group : '[]' ?>
        },
        vast: <?= $item->vast_url != '' ? $item->vast_url : 'null' ?>,
        pb: null,
        <?php if ($item->type_id == 4): ?>
            vid: {
            contents: <?= $item->widget_contents ?>,
            }
        <?php endif; ?>
    },

<?php } ?>


}