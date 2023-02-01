
<?php foreach ($data as $item) { ?>
    {
        "id": <?= $item->id ?>,
		"device": <?= json_encode($item->device, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
		"country": <?= json_encode($item->country, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
		"size": <?= json_encode($item->sizes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
		"time": <?= time() ?>,
    },
<?php } ?>
