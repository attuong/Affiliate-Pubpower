<?php if ($product) : ?>
    <?php if ($use_bootstrap_layout) : ?>
        <div class="shop-product-wrap col-md-3 col-sm-6 col-xs-6">
        <?php endif; ?>
        <?php if ($use_owl_wrapper) : ?>
            <div class="owl-shop-product-wrap">
            <?php endif; ?>
            <div class="shop-product shop-product_<?= $product->id ?>">
                <?php if (isset($product->sale_percent) && $product->sale_percent) : ?>
                    <div class="label-sale">-<?= $product->sale_percent ?>%</div>
                <?php endif; ?>
                <a href="<?= $product->url ?><?= ($add_ref_current_url) ? '?ref=' . urlencode(current_url()) : '' ?>">
                    <div class="overlay-wrapper">
                        <div class="product-thumb lazy--loading">
                            <img data-color-id="<?= $product->first_color_id ?>" class="lazy img-first" data-src="<?= build_resize_image_url($product->first_color_thumb, 350, 350) ?>" alt="Ảnh <?= $product->name ?>">
                            <?php if (isset($product->first_color_thumb_second) && $product->first_color_thumb_second) : ?>
                                <img data-color-id="<?= $product->first_color_id ?>" class="lazy img-hover" data-src="<?= build_resize_image_url($product->first_color_thumb_second, 350, 350) ?>" alt="Ảnh <?= $product->name ?> zoom">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="shop-product-info">
                        <?php if ($product->colors) : ?>
                            <div class="product-color-wrapper">
                                <div class="product-color">
                                    <?php foreach ($product->colors as $cl) : ?>
                                        <div class="color_preview_select color_preview_select_<?= $product->id ?>" data-product-id="<?= $product->id ?>" data-color-id="<?= $cl['color_id'] ?>" is-load="<?= ($product->first_color_id == $cl['color_id']) ? 'true' : 'false' ?>">
                                            <?php if ($cl['color_pattern_path']) : ?>
                                                <img class="lazy img-circle <?= ($product->first_color_id == $cl['color_id']) ? 'active' : '' ?>" data-src="<?= build_resize_image_url($cl['color_pattern_path'], 30, 30) ?>" style="width: 18px; height: 18px;" alt="Màu <?= $cl['color_name'] ?>" title="<?= $cl['color_name'] ?>"/>
                                            <?php else : ?>
                                                <div class="hex_color_pattern <?= ($product->first_color_id == $cl['color_id']) ? 'active' : '' ?>" data-color-id="<?= $cl['color_id'] ?>" style="background-color: <?= $cl['color_value'] ?>;" is-load="<?= ($product->first_color_id == $cl['color_id']) ? 'true' : 'false' ?>"></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="product-price">
                            <?php if (!isset($product->sale_percent)) : ?>
                                <span class="item-sale-price"><?= number_format($product->price) ?> <sup>đ</sup></span>
                            <?php else : ?>
                                <?php if ($product->sale_schedule == 1) : ?>
                                    <?php if ($product->sale_time_begin <= time() && $product->sale_time_end >= time()) : ?>
                                        <span class="item-price"><?= number_format($product->price) ?> <sup>đ</sup></span>
                                        <span class="item-sale-price"><?= number_format($product->promotional_price) ?> <sup>đ</sup></span>
                                    <?php else : ?>
                                        <span class="item-sale-price"><?= number_format($product->price) ?> <sup>đ</sup></span>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <span class="item-price"><?= number_format($product->price) ?> <sup>đ</sup></span>
                                    <span class="item-sale-price"><?= number_format($product->promotional_price) ?> <sup>đ</sup></span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <div class="product-name">
                            <h3 title="<?= $product->name ?>"><?= $product->name ?></h3>
                        </div>
                    </div>
                </a>
            </div>
            <?php if ($use_owl_wrapper) : ?>
            </div>
        <?php endif; ?>
        <?php if ($use_bootstrap_layout) : ?>
        </div>
    <?php endif; ?>
<?php endif; ?>