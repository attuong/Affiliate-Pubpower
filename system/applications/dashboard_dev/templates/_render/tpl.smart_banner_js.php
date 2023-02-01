[

<?php foreach ($data as $item) { ?>

    {
            zid: <?= $item->id ?>,
            adSlot: "<?= $item->adSlot ?>",
            floorPrice: <?= $item->floor_price ?>,
            adunit: {
            <?php foreach ($item->adunits as $adunit) : ?>

                "<?= $adunit['size']->width ?>_<?= $adunit['size']->height ?>" : {
                    code: '<?= $item->adSlot ?>',
                    mediaTypes: {
                        banner: {
                            sizes: [<?= $adunit['size']->width ?>, <?= $adunit['size']->height ?>]
                        }
                    },
                    bids: <?= $adunit['bids'] ?>
                },
                
            <?php endforeach; ?>
            }
        },

<?php } ?>

]