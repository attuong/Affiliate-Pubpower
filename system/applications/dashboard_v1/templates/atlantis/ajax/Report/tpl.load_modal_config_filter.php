<ul class="nav nav-primary">
    <?php if (!empty($list_configs)): ?>
        <?php foreach ($list_configs as $config): ?>
            <li class="nav-item <?= $config->id == $active ? 'active' : '' ?>" data-filters="<?= $config->filters ?>" data-config-id="<?= $config->id ?>">
                <a data-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                    <p><?= $config->name ?></p>
                </a>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
<div class="text-center mt-4">
    <button class="btn btn-flat btn-success add-config-filter"><i class="fas fa-plus"></i> Add New</button>
</div>