<div class="page-inner" id="payment-request">
    <div class="page-header">
        <h4 class="page-title">Link Affiliate</h4>
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
                <a href="#">Link Affiliate</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12 no-bd">
            <div class="card border-transparent mt-4">
                <div class="card-header <?= $theme_settings->card_header; ?>">
                    <div class="card-head-row">
                        <div class="card-title text-white">
                            <i class="fas fa-link mr-2"></i> Link Affiliate
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-8 pb-5" style="margin: 0 auto">
                        <?php if (!empty($is_system) && $is_system == "Pubpower"): ?>
                        <div class="form-row pb-4">
                            <div class="form-group col-md-12">
                                <label title="Valueimpression.com">Home (pubpower.io)</label>
                                <input type="text" readonly="" value="<?= 'https://pubpower.io?aff=' . $user->id ?>" class="form-control select-input">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label title="Sign Up Valueimpression">Sign Up (apps.pubpower.io)</label>
                                <input type="text" readonly="" value="<?= 'https://' . URL_REGISTER . '?aff=' . $user->id ?>" class="form-control select-input">
                            </div>
                        </div>
                        <?php else: ?>
                            <div class="form-row pb-4">
                                <div class="form-group col-md-12">
                                    <label title="Valueimpression.com">Home (valueimpression.com)</label>
                                    <input type="text" readonly="" value="<?= 'https://' . DOMAIN_MASTER . '?aff=' . $user->id ?>" class="form-control select-input">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label title="Sign Up Valueimpression">Sign Up (dashboard.valueimpression.com)</label>
                                    <input type="text" readonly="" value="<?= 'https://' . URL_REGISTER . '?aff=' . $user->id ?>" class="form-control select-input">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.select-input').click(function () {
            $(this).select();
        })
    })
</script>