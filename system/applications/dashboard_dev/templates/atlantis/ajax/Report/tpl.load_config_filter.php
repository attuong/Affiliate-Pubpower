<div class="config-item" data-config-id="<?= !empty($config_filter) ? $config_filter->id : 0 ?>">
    <div class="col-12 form-group">
        <label for="inputZip">Name</label>
        <input type="text" class="form-control config-name" <?= !empty($config_filter) && $config_filter->id == 1 ? 'readonly' : '' ?> placeholder="First name" value="<?= !empty($config_filter) ? $config_filter->name : '' ?>">
    </div>
    <div class="form-group">
        <label class="form-label d-block">Field</label>
        <div class="selectgroup selectgroup-pills">
            <?php if (!empty($fields)): ?>
                <?php foreach ($fields as $filter): ?>
                    <?php if (!$filter->type): ?>
                        <label class="selectgroup-item pr-2" filter-id="<?= $filter->id ?>">
                            <input type="checkbox" value="<?= $filter->id ?>" <?= !empty($config_filter) && in_array($filter->id, $config_filter->filters) ? 'checked' : '' ?> class="selectgroup-input input-filter">
                            <span class="selectgroup-button"><?= $filter->name ?></span>
                        </label>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label d-block">VLI Exchange</label>
        <div class="selectgroup selectgroup-pills">
            <?php if (!empty($fields)): ?>
                <?php foreach ($fields as $filter): ?>
                    <?php if ($filter->type == 'vli'): ?>
                        <label class="selectgroup-item pr-2" filter-id="<?= $filter->id ?>">
                            <input type="checkbox" value="<?= $filter->id ?>" <?= !empty($config_filter) && in_array($filter->id, $config_filter->filters) ? 'checked' : '' ?> class="selectgroup-input input-filter">
                            <span class="selectgroup-button"><?= $filter->name ?></span>
                        </label>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label d-block">AdSense/Ad Echange</label>
        <div class="selectgroup selectgroup-pills">
            <?php if (!empty($fields)): ?>
                <?php foreach ($fields as $filter): ?>
                    <?php if ($filter->type == 'adsense'): ?>
                        <label class="selectgroup-item pr-2" filter-id="<?= $filter->id ?>">
                            <input type="checkbox"  value="<?= $filter->id ?>" <?= !empty($config_filter) && in_array($filter->id, $config_filter->filters) ? 'checked' : '' ?> class="selectgroup-input input-filter">
                            <span class="selectgroup-button"><?= $filter->name ?></span>
                        </label>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php if (empty($config_filter) || $config_filter->id != 1): ?>
        <div class="form-group">
            <label class="form-label">Set Default</label>
            <input type="checkbox" value="1" class="set-default ml-3" <?= !empty($config_filter) && $config_filter->set_default == 1 ? 'checked' : '' ?>>
        </div>
    <?php endif; ?>
</div>
