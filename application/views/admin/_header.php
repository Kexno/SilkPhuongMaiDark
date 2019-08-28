<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo BASE_ADMIN_URL ?>" class="logo">
        <span class="logo-mini"><b>CMS</b></span>
        <span class="logo-lg"><b>NTA</b> CMS</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="javascript:void(0)" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="tasks-menu">
                    <a href="<?php echo base_url(); ?>" target="_blank" title="View Home">
                        <i class="fa fa-home"></i> Xem trang chủ
                    </a>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo $this->templates_assets;?>img/avatar.png" class="user-image" alt="<?php echo $this->session->user;?>">
                        <span class="hidden-xs"><?php echo $this->session->user;?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?php echo $this->templates_assets;?>img/avatar.png" class="img-circle" alt="User Image">
                            <p><?php echo $this->session->user;?></p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?php echo site_url('admin/users/profile');?>" class="btn btn-default btn-flat">Hồ sơ</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo site_url('admin/auth/logout');?>" class="btn btn-default btn-flat">Thoát</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
