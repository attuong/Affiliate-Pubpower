<?php if (!empty($bidder_unchange_items)): ?>
    <?php foreach ($bidder_unchange_items as $size_name => $items): ?>
        <div class="card-body border mb-4 vlibox">
            <h4 class="text-center pt-2 pb-2 vlibox-title"><span class="font-weight-bold">Size <?= $size_name ?></span></h4>
            <?php foreach ($items as $item): ?>
                <?= BidderBlock::getInstance()->bidder_item($item); ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="text-warning pb-3">
        Sorry, no bidder has been added to the system yet!
    </div>
<?php endif; ?>
