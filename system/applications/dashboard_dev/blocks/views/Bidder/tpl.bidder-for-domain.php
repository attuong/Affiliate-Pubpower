<style>
    .dropdown-toggle:after { content: none }
    .dropdown-menu {
        min-width: unset; 
    }
</style>

<li class="d-flex justify-content-between align-items-center mb-3">
    <i class="fas fa-desktop mr-3" data-toggle="tooltip" title="List bidder display" style="font-size:16px"></i>
    <small class="pr-1 ">
        <ul class="nav nav-pills nav-stacked">
            <?php foreach ($bidder_domain['displays'] as $demand): ?>
                <li class="demand-item mb-1 mr-1" data-demand="<?= $demand->bidder_id; ?>" data-domain="<?= $demand->domain_id; ?>" data-type="display">
                    <div class="btn-group pull-right">
                        <a href="javascript:void(0)" data-toggle="dropdown" class="dropdown-toggle">
                            <?= $demand->text; ?>,
                        </a>
                        <div role="menu" class="dropdown-menu">
                            <a data-status="approved" class="dropdown-item text-success edit-status-bidder"><b class="text-success">A</b>pproved</a>
                            <a data-status="pending" class="dropdown-item edit-status-bidder"><b>P</b>ending</a>
                            <a data-status="submited" class="dropdown-item text-warning edit-status-bidder"><b class="text-warning">S</b>ubmited</a>
                            <a data-status="reject" class="dropdown-item text-danger edit-status-bidder"><b class="text-danger">R</b>eject</a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </small>
    <small class="badge badge-count badge-pill" data-toggle="tooltip" title="Demand Display"><?= number_format(count($bidder_domain['displays'])); ?></small>
</li>
<li class="d-flex justify-content-between align-items-center">
    <i class="fas fa-play-circle mr-3" data-toggle="tooltip" title="List bidder video" style="font-size:16px"></i>
    <small class="pr-1 ">
        <ul class="nav nav-pills nav-stacked">
            <?php foreach ($bidder_domain['videos'] as $demand): ?>
                <li class="demand-item mb-1 mr-1" data-demand="<?= $demand->bidder_id; ?>" data-domain="<?= $demand->domain_id; ?>" data-type="video">
                    <div class="btn-group pull-right">
                        <a href="javascript:void(0)" data-toggle="dropdown" class="dropdown-toggle">
                            <?= $demand->text; ?>,
                        </a>
                        <div role="menu" class="dropdown-menu">
                            <a data-status="approved" class="dropdown-item text-success edit-status-bidder"><b class="text-success">A</b>pproved</a>
                            <a data-status="pending" class="dropdown-item edit-status-bidder"><b>P</b>ending</a>
                            <a data-status="submited" class="dropdown-item text-warning edit-status-bidder"><b class="text-warning">S</b>ubmited</a>
                            <a data-status="reject" class="dropdown-item text-danger edit-status-bidder"><b class="text-danger">R</b>eject</a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </small>
    <small class="badge badge-count badge-pill" data-toggle="tooltip" title="Demand Video"><?= number_format(count($bidder_domain['videos'])); ?></small>
</li>

