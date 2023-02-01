<?php if (!empty($item) && empty($type)): ?>
    <div class="bidder-item <?= $item->status != 'on' ? 'bg-grey1' : ''; ?>" bidder-item-id="<?= $item->id ?>" bidder-name="<?= $item->bidder ?>" bidder-item-status="<?= $item->status; ?>" bidder-item-size="<?= $item->size_id; ?>">
        <h4 class="mb-3">
            <div class="btn-group">
                <?php if ($item->status == 'on'): ?>
                    <button data-toggle="dropdown" type="button" class="btn btn-xs dropdown-toggle text-uppercase btn-success">
                        <i class="fas fa-power-off mr-1"></i> ON&nbsp;
                    </button>
                <?php else: ?>
                    <button data-toggle="dropdown" type="button" class="btn btn-xs dropdown-toggle text-uppercase">
                        <i class="fas fa-ban mr-1"></i> OFF&nbsp;
                    </button>
                <?php endif; ?>
                <div role="menu" class="dropdown-menu">
                    <a href="javascript:void(0);" class="dropdown-item text-success" data-status="on" data-style="btn-success"><i class="fas fa-power-off mr-1"></i> ON&nbsp;</a>
                    <a href="javascript:void(0);" class="dropdown-item" data-status="off" data-style=""><i class="fas fa-ban mr-1"></i> OFF&nbsp;</a>
                    <div class="dropdown-divider"></div>
                    <a href="javascript:void(0);" class="dropdown-item text-danger delete-bidder"><i class="fas fa-trash mr-1"></i> Delete</a>
                </div>
            </div>
            <span class="ml-3 <?= $item->status == 'on' ? 'font-weight-bold' : 'text-muted'; ?>"><?= $item->bidder; ?></span>
        </h4>
        <div class="form-row mb-4">
            <?php if (!empty($item->params)): ?>
                <?php foreach ($item->params as $value): ?>
                    <div class="<?= $device == 'phone' ? 'col-md-12' : 'col'; ?> form-group bidder-item-params">
                        <?php if (!is_object($value['params']) && !is_array($value['params'])): ?>
                            <input type="<?= (in_array($value['type'], ['int', 'float']) ? 'text' : 'text') ?>" data-name="<?= $value['name'] ?>" data-type="<?= $value['type'] ?>" class="form-control <?= $item->status != 'on' ? 'bg-grey2' : ''; ?>" placeholder="<?= $value['name']; ?>" value="<?= is_bool($value['params']) ? boolean_to_string($value['params']) : $value['params'] ?>" data-toggle="tooltip" title="<?= $value['name']; ?>">
                        <?php else: ?>
                            <textarea data-name="<?= $value['name'] ?>" data-type="<?= $value['type'] ?>" placeholder="<?= $value['name']; ?>" data-toggle="tooltip" title="<?= $value['name']; ?>" class="form-control <?= $item->status != 'on' ? 'bg-grey2' : ''; ?>" style="height:42px;"><?= json_encode($value['params'], JSON_UNESCAPED_SLASHES); ?></textarea>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($bidder) && empty($sizes)): ?>
    <div class="bidder-item" bidder-item-id="0" bidder-name="<?= $bidder->name ?>" bidder-item-status="on">
        <h4 class="mb-3">
            <div class="btn-group">
                <button data-toggle="dropdown" type="button" class="btn btn-xs dropdown-toggle text-uppercase btn-success">
                    <i class="fas fa-power-off mr-1"></i> ON&nbsp;
                </button>
                <div role="menu" class="dropdown-menu">
                    <a href="javascript:void(0);" class="dropdown-item text-success" data-status="on" data-style="btn-success"><i class="fas fa-power-off mr-1"></i> ON&nbsp;</a>
                    <a href="javascript:void(0);" class="dropdown-item" data-status="off" data-style=""><i class="fas fa-ban mr-1"></i> OFF&nbsp;</a>
                    <div class="dropdown-divider"></div>
                    <a href="javascript:void(0);" class="dropdown-item text-danger delete-bidder"><i class="fas fa-trash mr-1"></i> Delete</a>
                </div>
            </div>
            <span class="ml-3 <?= $bidder->status == 'on' ? 'font-weight-bold' : 'text-muted'; ?>"><?= $bidder->name; ?></span>
        </h4>
        <div class="form-row mb-4">
            <?php if ($bidder->params): ?>
                <?php foreach ($bidder->params as $value): ?>
                    <div class="<?= $device == 'phone' ? 'col-md-12' : 'col'; ?> form-group bidder-item-params">
                        <?php if ($value->name == 'video' || in_array($value->type, ['json'])): ?>
                            <textarea class="form-control" data-name="<?= $value->name; ?>" data-type="<?= $value->type ?>" placeholder="<?= $value->name . '...' ?>" data-toggle="tooltip" title="<?= $value->name ?>" style="height:42px;"><?= $value->example ?></textarea>
                        <?php else: ?>
                            <input class="form-control" type="<?= in_array($value->type, ['float', 'int']) ? 'text' : 'text'; ?>" data-name="<?= $value->name; ?>" data-type="<?= $value->type ?>" placeholder="<?= $value->name . '...'; ?>" value="<?= $value->example; ?>" data-toggle="tooltip" title="<?= $value->name; ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($bidder) && !empty($sizes)): ?>
    <div class="bidder-item mb-3" bidder-item-id="0" bidder-name="<?= $bidder->name ?>" bidder-item-status="on" bidder-item-size="">
        <?php if (!empty($sizes)): ?>
            <div class="select-bidder-item-size">
                <div class="<?= $device == 'phone' ? 'col-md-12' : 'col'; ?> form-group">
                    <label><?= $bidder->name ?></label>
                    <select class="select2 form-control add-bidder-select-size" data-placeholder="select a size" data-width="100%">
                        <option></option>
                        <?php foreach ($sizes as $size): ?>
                            <option value="<?= $size->id ?>"><?= $size->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        <?php endif; ?>
        <div class="bd-item d-none">
            <h4>
                <div class="form-group btn-group">
                    <button data-toggle="dropdown" type="button" class="btn btn-xs dropdown-toggle text-uppercase btn-success">
                        <i class="fas fa-power-off mr-1"></i> ON 
                    </button>
                    <div role="menu" class="dropdown-menu">
                        <a href="javascript:void(0);" class="dropdown-item text-success" data-status="on" data-style="btn-success"><i class="fas fa-power-off mr-2"></i> ON </a>
                        <a href="javascript:void(0);" class="dropdown-item" data-status="off" data-style=""><i class="fas fa-ban mr-2"></i> OFF </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0);" class="dropdown-item text-danger delete-bidder"><i class="fas fa-trash mr-2"></i> Delete <small class="text-muted ml-3">(<i>####</i>)</small></a>
                    </div>
                </div>
                <span class="font-weight-bold"><?= $bidder->name; ?></span> <span class="result-size"></span>
            </h4>
            <div class="form-row mb-4" >
                <?php if ($bidder->params): ?>
                    <?php foreach ($bidder->params as $value): ?>
                        <div class="<?= $device == 'phone' ? 'col-md-12' : 'col'; ?> form-group bidder-item-params">
                            <?php if ($value->name == 'video' || in_array($value->type, ['json'])): ?>
                                <textarea class="form-control bidder-item-params-input" data-name="<?= $value->name; ?>" data-type="<?= $value->type ?>" placeholder="<?= $value->name . '...' ?>" data-toggle="tooltip" title="<?= $value->name ?>" style="height:42px;"><?= $value->example ?></textarea>
                            <?php else: ?>
                                <input class="form-control bidder-item-params-input" type="<?= in_array($value->type, ['float', 'int']) ? 'text' : 'text'; ?>" data-name="<?= $value->name; ?>" data-type="<?= $value->type ?>" placeholder="<?= $value->name . '...'; ?>" value="<?= $value->example; ?>" data-toggle="tooltip" title="<?= $value->name; ?>">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php if (!empty($item) && !empty($type)): ?>
    <div class="bidder-item <?= $item->status != 'on' ? 'bg-grey1' : ''; ?> d-block" bidder-item-id="<?= $item->id ?>" bidder-name="<?= $item->bidder ?>" bidder-item-status="<?= $item->status; ?>" bidder-item-size="<?= $item->size_id; ?>">
        <h4 class="mb-3">
            <div class="btn-group">
                <?php if ($item->status == 'on'): ?>
                    <button data-toggle="dropdown" type="button" class="btn btn-xs dropdown-toggle text-uppercase btn-success">
                        <i class="fas fa-power-off mr-1"></i> ON&nbsp;
                    </button>
                <?php else: ?>
                    <button data-toggle="dropdown" type="button" class="btn btn-xs dropdown-toggle text-uppercase">
                        <i class="fas fa-ban mr-1"></i> OFF&nbsp;
                    </button>
                <?php endif; ?>
                <div role="menu" class="dropdown-menu">
                    <a href="javascript:void(0);" class="dropdown-item text-success" data-status="on" data-style="btn-success"><i class="fas fa-power-off mr-1"></i> ON&nbsp;</a>
                    <a href="javascript:void(0);" class="dropdown-item" data-status="off" data-style=""><i class="fas fa-ban mr-1"></i> OFF&nbsp;</a>
                </div>
            </div>
            <span class="ml-3 <?= $item->status == 'on' ? 'font-weight-bold' : 'text-muted'; ?>"><?= $item->bidder; ?></span>
        </h4>
        <div class="form-row mb-4">
            <?php if (!empty($item->params)): ?>
                <?php foreach ($item->params as $value): ?>
                    <div class="<?= $device == 'phone' ? 'col-md-12' : 'col'; ?> form-group bidder-item-params">
                        <?php if (!is_object($value['params']) && !is_array($value['params'])): ?>
                        <input type="<?= (in_array($value['type'], ['int', 'float']) ? 'text' : 'text') ?>" data-name="<?= $value['name'] ?>" data-type="<?= $value['type'] ?>" class="form-control <?= $item->status != 'on' ? 'bg-grey2' : ''; ?>" placeholder="<?= $value['name']; ?>" value="<?= is_bool($value['params']) ? boolean_to_string($value['params']) : $value['params'] ?>" data-toggle="tooltip" title="<?= $value['name']; ?>" readonly="">
                        <?php else: ?>
                            <textarea data-name="<?= $value['name'] ?>" data-type="<?= $value['type'] ?>" placeholder="<?= $value['name']; ?>" data-toggle="tooltip" title="<?= $value['name']; ?>" class="form-control <?= $item->status != 'on' ? 'bg-grey2' : ''; ?>" style="height:42px;" readonly=""><?= json_encode($value['params'], JSON_UNESCAPED_SLASHES); ?></textarea>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
