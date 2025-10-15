<div class="left-side-bar">
    <div class="brand-logo">
        <a href="index.html">
            <img src="<?= base_url('images/setting/' . get_setting()->blog_logo) ?>" alt="" class="dark-logo" />
            <img
                src="/backend/vendors/images/deskapp-logo-white.svg"
                alt=""
                class="light-logo" />
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <li>
                    <a href="<?= route_to('admin.home') ?>" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-house"></span><span class="mtext">Home</span>
                    </a>
                </li>
                <!-- <li>
                    <a href="calendar.html" class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-calendar4-week"></span><span class="mtext">Categories</span>
                    </a>
                </li> -->
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-calendar4-week"></span><span class="mtext">Categories</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="<?= route_to('admin.category.list') ?>">All category</a></li>
                        <li><a href="<?= route_to('admin.category.add') ?>">Add category</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="micon bi bi-calendar4-week"></span><span class="mtext">Posts</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="basic-table.html">All posts</a></li>
                        <li><a href="<?= route_to('admin.post.create') ?>">Add new</a></li>
                    </ul>
                </li>

                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <div class="sidebar-small-cap">Setting</div>
                </li>
                
                <li>
                    <a
                        href="<?= route_to('admin.profile') ?>"
                        class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-layout-text-window-reverse"></span>
                        <span class="mtext">Profile</span>
                    </a>
                </li>
                <li>
                    <a
                        href="<?= route_to('admin.setting') ?>"
                        class="dropdown-toggle no-arrow">
                        <span class="micon bi bi-layout-text-window-reverse"></span>
                        <span class="mtext">Setting</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>