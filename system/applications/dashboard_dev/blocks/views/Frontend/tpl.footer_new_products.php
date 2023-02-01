<?php if ($footer_new_products) : ?>
    <h5>Sản phẩm mới</h5>
    <div class="photo">
        <div class="row">
            <?php foreach ($footer_new_products as $value) : ?>
                <div class="col-xs-4 col-sm-2 col-md-4">
                    <a href="<?= $value->url ?>" title="<?= $value->name ?>">
                        <div class="lazy--loading" style="padding-bottom: 100%;">
                            <img class="lazy fluid-width" data-src="<?= build_resize_image_url($value->thumb, 150, 150) ?>" alt="Ảnh <?= $value->name ?> 150x150">
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>