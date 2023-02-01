<style>
    #jqclock, .clockdate, .clocktime, .icon-clock {
        color: #b9babf !important;
    }
</style>
<!-- Sidebar -->
<div class="sidebar sidebar-style-2" data-background-color="<?= $theme_settings->sidebar; ?>">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav <?= $theme_settings->sidebar_nav; ?>">
                <li class="nav-item <?= in_array($URLSchemesController, ['frontend/Report']) ? 'active' : ''; ?>">
                    <a href="<?= URL_REPORT; ?>" title="Reports">
                        <i class="fas fa-chart-bar"></i>
                        <p>Reports</p>
                    </a>
                </li>
                <li class="nav-item <?= in_array($URLSchemesController, ['frontend/Domain']) ? 'active' : ''; ?>">
                    <a href="<?= URL_DOMAIN; ?>" title="Websites">
                        <i class="fas fa-globe-americas"></i>
                        <p>Websites</p>
                    </a>
                </li>
                <li class="nav-item <?= in_array($URLSchemesController, ['frontend/Payment']) ? 'active' : ''; ?>">
                    <a href="<?= URL_PAYMENT; ?>" title="Payment">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <p>Payments</p>
                    </a>
                </li>
                <li class="nav-item <?= in_array($URLSchemesController, ['frontend/Link']) ? 'active' : ''; ?>">
                    <a href="<?= URL_LINK_AFFILIATE; ?>" title="Link Affiliate">
                        <i class="fas fa-link"></i>
                        <p>Link Affiliate</p>
                    </a>
                </li>
                <li class="nav-item <?= in_array($URLSchemes, ['frontend/User/setting']) ? 'active' : ''; ?>">
                    <a href="<?= URL_USER; ?>" title="Setting User">
                        <i class="fas fa-user-edit"></i>
                        <p>Profile</p>
                    </a>
                </li>
                <li class="nav-item <?= in_array($URLSchemes, ['frontend/User/billing']) ? 'active' : ''; ?>">
                    <a href="<?= URL_USER_BILLING; ?>" title="Setting User">
                        <i class="fas fa-money-check-alt"></i>
                        <p>Billing</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= URL_LOGOUT; ?>">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->