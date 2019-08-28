<?php $listCate=getListCate(); ?>
<div class="kontainer-full">
    <!-- BACKGROUND DECORATION-->

    <div id="curtain">
        <img id="cb" src="<?php echo base_url(); ?>public/images/common/curtain-back.svg">
        <img id="cf" src="<?php echo base_url(); ?>public/images/common/curtain-front.svg">
    </div>
    <img id="wb" src="<?php echo base_url(); ?>public/images/common/white-mulberry.svg">

    <!-- NAVIGATOR BAR -->

    <nav class="navbar navbar-expand-xl fixed-top navbar-dark">
        <!--NAVBAR BACKGROUND-->
        <div id="topbar-bg" class="darkbg shadowdark"></div>
        <!-- logo -->
        <a class="navbar-brand" href="<?php echo base_url(); ?>"><img id="logo"  src="<?php echo base_url(); ?>public/images/common/logo-slogan-bh.svg"></a>
        <!-- navbar toggler -->
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar1">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- menu -->
        <div class="collapse navbar-collapse" id="navbar1">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active buttonds" href="<?php echo base_url(); ?>">TRANG CHỦ</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle buttonds" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        GIỚI THIỆU
                    </a>
                    <div class="dropdown-menu darkbg shadowdark" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="<?php echo base_url('tong-quan.html'); ?>">Tổng quan</a>
                        <a class="dropdown-item" href="<?php echo base_url('ve-chung-toi.html'); ?>">Về chúng tôi</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a href="product.html" data-toggle="dropdown" class="nav-link dropdown-toggle buttonds">SẢN PHẨM</a>
                    <ul class="dropdown-menu darkbg shadowdark">
                        <li>
                            <a class="dropdown-item" href="<?php echo base_url('tong-quan-san-pham.html'); ?>">Tổng quan</a>
                        </li>
                        <?php if(!empty($listCate)) foreach ($listCate as $key => $value) { if($value->parent_id==0){ ?>
                            <li class="dropdown-item dropdown-submenu">
                                <a data-toggle="dropdown collapse" class="dropdown-toggle" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $value->title; ?></a>
                                <ul class="dropdown-menu darkbg shadow">
                                    <?php foreach ($listCate as $k => $v) { if($v->parent_id==$value->id){ ?>
                                        <li>
                                            <a  class="dropdown-item" href="<?php echo getUrlCateProduct($v); ?>"><?php echo $v->title; ?></a>
                                        </li>
                                    <?php }} ?>
                                </ul>
                            </li>
                        <?php }} ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link buttonds" href="<?php echo base_url('tin-tuc-c2'); ?>">TIN TỨC</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link buttonds" href="<?php echo base_url('lien-he.html'); ?>">LIÊN HỆ</a>
                </li>
            </ul>
            <!-- language -->
            <ul class="navbar-nav ml-auto" id="language">
                <?php if($this->session->public_lang_code=='en'){ ?>
                  <li class="nav-item d-none d-xl-block">
                    <a class="vi active" href="#">Tiếng Anh</a>
                    <a class="en" onclick="chooseLang('vi');" href="#">Tiếng Việt</a>
                </li>
            <?php }else{ ?>
                <li class="nav-item d-none d-xl-block">
                    <a class="vi active" href="javascript:;">Tiếng Việt</a>
                    <a class="en" onclick="chooseLang('en');" href="javascript:;">Tiếng Anh</a>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>

<!-- phone numbers-->

<div id="phones" class="row justify-content-center">
    <a class="col-auto bluebg shadowlight" href="tel:<?php echo $this->settings['contact'][$this->session->public_lang_code]['phone']; ?>">
        <img id="call" src="<?php echo base_url(); ?>public/images/common/call.svg">
        Hotline: <?php echo $this->settings['contact'][$this->session->public_lang_code]['phone']; ?>
    </a>
</div>
</div>