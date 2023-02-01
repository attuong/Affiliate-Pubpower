{

<?php foreach ($data as $item) { ?>

    <?php if ($item->type_id == 0): ?> <?php // Tag Type: Display ?>

        "<?= $item->adSlot ?>" : {
            zid: <?= $item->id ?>,
            w: <?= $item->size->width ?>,
            h: <?= $item->size->height ?>,
            type: "normal",
            noPublisherPb: <?= $item->publisher_passback == '' ? 'true' : 'false' ?>,
            floorPrice: <?= $item->floor_price ?>,
            adunit: {
                code: '<?= $item->adSlot ?>',
                mediaTypes: {
                    banner: {
                        sizes: <?= $item->size->sizes == '' ? '[300,250]' : $item->size->sizes ?>
                    }
                },
                bids: null
            }
        },

    <?php endif; ?>


    <?php if ($item->type_id == 6): ?> <?php // Tag Type: Display Fixed ?>

        "<?= $item->adSlot ?>" : {
            zid: <?= $item->id ?>,
            w: <?= $item->size->width ?>,
            h: <?= $item->size->height ?>,
            type: "fixed",
            noPublisherPb: <?= $item->publisher_passback == '' ? 'true' : 'false' ?>,
            floorPrice: <?= $item->floor_price ?>,
            adunit: {
                code: '<?= $item->adSlot ?>',
                mediaTypes: {
                    banner: {
                        sizes: []
                    }
                },
                bids: null
            }
        },

    <?php endif; ?>

<?php } ?>

}