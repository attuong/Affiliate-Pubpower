<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="sidebar-search">
                <form method="GET" action="<?= URL_ADMIN_PRODUCT_LIST; ?>">
                    <div class="input-group custom-search-form">
                        <input type="text" name="f-keyword" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                    <!-- /input-group -->
                </form>
            </li>
            <li>
                <a href="<?= URL_ADMIN_DASHBOARD; ?> "><i class="fa fa-dashboard fa-fw"></i> BẢNG ĐIỀU KHIỂN </a>
            </li>
            <li>
                <a href="javascript:void();"><i class="fa fa-video-camera" aria-hidden="true"></i> VIDEO <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li>
                        <a href="<?= URL_ADMIN_VIDEO_LIST; ?>">Danh sách video</a>
                    </li>
                    <li>
                        <a href="<?= URL_ADMIN_CATEGORY_LIST; ?>">Danh mục video</a>
                    </li>
                    <li>
                        <a href="<?= URL_ADMIN_VIDEO_CREATE; ?>">Thêm video</a>
                    </li>
                    <li>
                        <a href="<?= URL_ADMIN_CATEGORY_CREATE; ?>?object_type=<?= VIDEO; ?>">Thêm danh mục video</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            <li>
                <a href="javascript:void();"><i class="fa fa-commenting" aria-hidden="true"></i> DIỄN ĐÀN <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li>
                        <a href="<?= URL_ADMIN_FORUM_LIST; ?>">Danh sách diễn đàn</a>
                    </li>
                    <li>
                        <a href="<?= URL_ADMIN_CATEGORY_LIST; ?>?f-object_type=FORUM">Danh mục diễn đàn</a>
                    </li>
                    <li>
                        <a href="<?= URL_ADMIN_CATEGORY_CREATE; ?>?object_type=FORUM">Tạo danh mục</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            <li>
                <a href="javascript:void();"><i class="fa fa-truck" aria-hidden="true"></i> BLOG <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li>
                        <a href="<?= URL_ADMIN_BLOG_LIST; ?>">Danh sách blog</a>
                    </li>
                    <li>
                        <a href="<?= URL_ADMIN_CATEGORY_LIST; ?>?f-object_type=BLOG">Danh mục blog</a>
                    </li>
                    <li>
                        <a href="<?= URL_ADMIN_BLOG_CREATE; ?>">Viết blog mới</a>
                    </li>
                    <li>
                        <a href="<?= URL_ADMIN_CATEGORY_CREATE; ?>?object_type=BLOG">Thêm danh mục blog</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            <li>
                <a href="javascript:void();"><i class="fa fa-files-o fa-fw" aria-hidden="true"></i> TRANG <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li>
                        <a href="<?= URL_ADMIN_PAGE_LIST; ?>">Danh sách trang</a>
                    </li>
                    <li>
                        <a href="<?= URL_ADMIN_PAGE_CREATE; ?>">Tạo trang</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->