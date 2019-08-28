<?php
$controller = $this->router->fetch_class();
$method = $this->router->fetch_method();
if ($controller == 'home') {
    ?>
    <div class="slide-home wow fadeInLeft" data-wow-duration="1.5s">
        <div class="slidehome owl-carousel owl-theme">
            <?php
            $slides = $this->settings['slides'];
            if (!empty($slides)) foreach ($slides as $slide) {
                ?>
                <div class="item">
                    <img src="<?php echo getImageThumb($slide[$this->session->public_lang_code]['img']) ?>"
                         alt="<?php echo $slide[$this->session->public_lang_code]['title'] ?>">
                    <div class="title">
                        <h2><?php echo $slide[$this->session->public_lang_code]['title']; ?></h2>
                        <p><?php echo $slide[$this->session->public_lang_code]['slogan']; ?></p>
                    </div>
                </div>
                <?php
            }
            ?>

        </div>
    </div>
    <?php
}
?>
<!--Begin: header-->
<header class="wow fadeInRight <?php if ($controller !== 'home') echo 'goOut page'; ?>" data-wow-duration="1.5s">
    <div class="logo">
        <a href="<?php echo site_url('/') ?>"
           title="<?php echo isset($this->settings['title']) ? $this->settings['title'] : $this->settings['name']; ?>">
            <img
                src="<?php echo !(empty($this->settings['logo'])) ? getImageThumb($this->settings['logo']) : $this->templates_assets . 'images/logo.png'; ?>"
                alt="<?php echo isset($this->settings['title']) ? $this->settings['title'] : $this->settings['name']; ?>"></a>
    </div>
    <div class="menu-primary">
        <div class="form-search-home">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="<?php echo lang('text_search') ?>" name="search">
                <button type="submit" class="btnSearch"><i class="fa fa-search"></i></button>
            </div>
            <a class="close-search" href="javascript:;" title=""><i class="fa fa-times"></i></a>
            <div id="thongbao"></div>

        </div>
        <?php echo topnavbar('', '', 'sub-menu'); ?>
    </div>
    <div class="button-menu"></div>
    <div class="icon-search-home">
        <img src="<?php echo $this->templates_assets ?>images/icon-search-home.png" alt="Search">
    </div>
    <div class="lang">
        <?php
        $lang_no_active = 'vi';
        if ($this->session->public_lang_code == 'vi') {
            $lang_no_active = 'en';
        }
        ?>
        <a href="<?php echo $language[$lang_no_active] ?>" data-lang-code="<?php echo $lang_no_active ?>"><?php echo $lang_no_active ?></a>
    </div>
</header>
<!--End: header-->
<main id="main">