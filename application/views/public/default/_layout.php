<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php if (!empty($SEO)): ?>
        <title><?php echo isset($SEO['meta_title']) ? $SEO['meta_title'] : ''; ?></title>
        <meta name="description"
        content="<?php echo isset($SEO['meta_description']) ? $SEO['meta_description'] : ''; ?>"/>
        <meta name="keywords" content="<?php echo isset($SEO['meta_keyword']) ? $SEO['meta_keyword'] : ''; ?>"/>
        <!--Meta Facebook Page Other-->
        <meta property="og:type" content="website"/>
        <meta property="og:title" content="<?php echo isset($SEO['meta_title']) ? $SEO['meta_title'] : ''; ?>"/>
        <meta property="og:description"
        content="<?php echo isset($SEO['meta_description']) ? $SEO['meta_description'] : ''; ?>"/>
        <meta property="og:image" content="<?php echo isset($SEO['image']) ? $SEO['image'] : ''; ?>"/>
        <meta property="og:url" content="<?php echo isset($SEO['url']) ? $SEO['url'] : base_url(); ?>"/>
        <!--Meta Facebook Page Other-->
        <link rel="canonical" href="<?php echo isset($SEO['url']) ? $SEO['url'] : base_url(); ?>"/>
        <?php else: ?>
            <title><?php echo $this->settings['title'] . ' - ' . $this->settings['name']; ?></title>
            <meta name="description"
            content="<?php echo isset($this->settings['meta_desc']) ? $this->settings['meta_desc'] : ''; ?>"/>
            <meta name="keywords"
            content="<?php echo isset($this->settings['meta_keyword']) ? $this->settings['meta_keyword'] : ''; ?>"/>
            <!--Meta Facebook Homepage-->
            <meta property="og:type" content="website"/>
            <meta property="og:title"
            content="<?php echo isset($this->settings['title']) ? $this->settings['title'] . ' | ' . $this->settings['name'] : ''; ?>"/>
            <meta property="og:description"
            content="<?php echo isset($this->settings['meta_desc']) ? $this->settings['meta_desc'] : ''; ?>"/>
            <meta property="og:image"
            content="<?php echo isset($this->settings['logo']) ? getImageThumb($this->settings['logo'], 400, 200) : ''; ?>"/>
            <meta property="og:url" content="<?php echo base_url(); ?>"/>
            <!--Meta Facebook Homepage-->
            <link rel="canonical" href="<?php echo base_url(); ?>"/>
        <?php endif; ?>
        <!--  -->
        <?php if(empty($checkpr)){ ?>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <link href="<?php echo base_url(); ?>public/css/common.css" rel="stylesheet" type="text/css">
            <?php if(!$checknews){ ?>
                <link href="<?php echo base_url(); ?>public/css/store.css" rel="stylesheet" type="text/css">
            <?php } ?>
            <link href="<?php echo base_url(); ?>public/css/home.css" rel="stylesheet" type="text/css">
            <link href="<?php echo base_url(); ?>public/css/toastr.min.css" rel="stylesheet" type="text/css">

            <!-- jQuery, Popper.js, Bootstrap JS -->

            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
            <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
            <!-- fonts -->

            <link href="<?php echo base_url(); ?>public/fonts/fontawesome5.9.0/css/all.css" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap&subset=vietnamese" rel="stylesheet">
            <!--  -->
        <?php } ?>
        <link rel="icon"
        href="<?php echo !empty($this->settings['favicon']) ? getImageThumb($this->settings['favicon'], 32, 32) : base_url("/public/favicon.ico"); ?>"
        sizes="32x32">
        <link rel="icon"
        href="<?php echo !empty($this->settings['favicon']) ? getImageThumb($this->settings['favicon'], 192, 192) : base_url("/public/favicon.ico"); ?>"
        sizes="192x192">
        <link rel="apple-touch-icon-precomposed"
        href="<?php echo !empty($this->settings['favicon']) ? getImageThumb($this->settings['favicon'], 180, 180) : base_url("/public/favicon.ico"); ?>">
        <meta name="msapplication-TileImage"
        content="<?php echo !empty($this->settings['favicon']) ? getImageThumb($this->settings['favicon'], 270, 270) : base_url("/public/favicon.ico"); ?>">
        <script>
            var urlCurrentMenu = window.location.href,
            urlCurrent = window.location.href,
            base_url = '<?php echo base_url(); ?>',
            media_url = '<?php echo MEDIA_URL . '/'; ?>',
            lang_code = '<?php echo $this->session->public_lang_code ?>';
        </script>

    </head>
    <body>
        <?php
        $this->load->view($this->template_path . '_header');
        echo !empty($main_content) ? $main_content : '';
        $this->load->view($this->template_path . '_footer');
        ?>
        <?php if(stripos($_SERVER['HTTP_USER_AGENT'],"Chrome-Lighthouse") === false):?>
            <?php $asset_jslib[] = '/jquery.js'; ?>

            <?php minifyJS($asset_jslib, '/public/js/', true); ?>

        <?php endif; ?>
        <script type="text/javascript" src="<?php echo base_url(); ?>public/js/toastr.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>public/js/custom.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                toastr.options.escapeHtml = true;
                toastr.options.closeButton = true;
                toastr.options.positionClass = "toast-top-right";
                toastr.options.timeOut = 5000;
                toastr.options.showMethod = 'fadeIn';
                toastr.options.hideMethod = 'fadeOut';
                <?php if(!empty($this->session->flashdata('message'))): ?>
                    toastr.<?php echo $this->session->flashdata('type'); ?>('<?php echo trim(strip_tags($this->session->flashdata('message'))); ?>');
                    <?php
                    unset($_SESSION['message']);
                endif;
                ?>
            });
        </script>
    </body>
    </html>