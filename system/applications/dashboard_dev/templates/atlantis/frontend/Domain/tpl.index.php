<div class="page-inner" id="report-manager">
    <div class="page-header">
        <h4 class="page-title">Websites</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="<?= ROOTDOMAIN; ?>" title="Dashboard">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Websites</a>
            </li>
        </ul>
    </div>

    <?php if ($device != 'phone'): ?>
        <style>@media (min-width: 992px) {.content{overflow:inherit !important;}}</style>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-12 no-bd" id="custom-scroll">
            <div class="card border-transparent">
                <div class="card-header <?= $theme_settings->card_header; ?>">
                    <div class="card-head-row">
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-12" style="width: 300px">
                                    <select class="form-control select2" name="" onchange="submit()" data-allow-clear="true" data-placeholder="Select By Domain">
                                        <option value=""></option>
                                        <?php if (!empty($domains)): ?>
                                            <?php foreach ($domains as $domain): ?>
                                                <option value="<?= $domain->id ?>" <?= !empty($filters['domain_id']) && $filters['domain_id'] == $domain->id ? 'selected' : '' ?>><?= $domain->name ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body" id="result-ajax-report" style="min-height: 500px;">
                    <div class="col-md-12 pt-3 <?= $device == 'phone' ? 'p-0' : '' ?>">
                        <div class="<?= $device == 'phone' ? 'table-responsive' : 'table-responsive---scroll' ?>"> 
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Domain</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($domains)): ?>
                                        <?php foreach ($domains as $key => $domain): ?>
                                            <tr style="letter-spacing: 1px" data-month="<?= $report->month ?>">
                                                <td><?= ($page - 1) * 15 + $key + 1 ?></td>
                                                <td>
                                                    <a href="//<?= $domain->name ?>" target="_blank"><?= $domain->name ?></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="100%">No data</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-grey1">
                    <div class="row">
                        <?php if ($pagination->total) : ?>
                            <div class="col-sm-5">
                                <?= $pagination->show_counter(); ?>
                            </div>
                            <div class="col-sm-7">
                                <?= $pagination->show_with_ul(); ?>
                                <style>.pagination{margin: 0;float: right;}</style>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


