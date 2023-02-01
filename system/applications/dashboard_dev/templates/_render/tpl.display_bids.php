{

<?php foreach ($data as $key => $item) { ?>

    "<?= $item['size']->width . '_' . $item['size']->height ?>" : [
    <?php foreach ($item['bidder_group'] as $val) { ?>

        {
        used: false,
        bids: <?= $val ?>
        },

    <?php } ?>
    ],

<?php } ?>

}