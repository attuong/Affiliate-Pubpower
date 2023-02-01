{

<?php foreach ($data as $item) { ?>

    "<?= $item->adSlot ?>" : {
        zid: <?= $item->id ?>, 
        w: <?= $item->size->width ?>, 
        h: <?= $item->size->height ?>,
        floorPrice: <?= $item->floor_price ?>,
        type: "<?= $item->type_id == 5 ? 'inline' : 'slider' ?>",
        adunit: {
            code: '<?= $item->adSlot ?>',
            mediaTypes: {
                video: {
                    playerSize: [410,231],
                    context: 'outstream',
                    mimes: ['video/mp4', 'video/x-flv', 'video/x-ms-wmv', 'application/vnd.apple.mpegurl', 'application/x-mpegurl', 'video/3gpp', 'video/mpeg', 'video/ogg', 'video/quicktime', 'video/webm', 'video/x-m4v', 'video/ms-asf', 'video/x-msvideo'],
                    protocols: [1, 2, 3, 4, 5, 6],
                    playbackmethod: [6],
                    maxduration: 120,
                    linearity: 1,
                    api: [2]
                }
            },
            bids: <?= $item->bidder_group ?>,
            renderer: {
                url: '',
                render: function () {}
            }
        },
        vast: <?= $item->vast_url != '' ? $item->vast_url : 'null' ?>
    },

<?php } ?>

}