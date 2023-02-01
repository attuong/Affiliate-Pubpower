<?php if (!empty($bidder_unchange_items)): ?>
    <?php foreach ($bidder_unchange_items as $item): ?>
        <?= BidderBlock::getInstance()->bidder_item($item); ?>
    <?php endforeach; ?>
<?php else: ?>
    <div class="text-warning pb-3">
        Sorry, no bidder has been added to the system yet!
    </div>
<?php endif; ?>