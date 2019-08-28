<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Log in | CMS</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo base_url() ?>/public/admin/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() ?>/public/admin/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url() ?>/public/admin/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() ?>/public/admin/css/AdminLTE.min.css">

    <link rel="stylesheet" href="<?php echo base_url() ?>/public/admin/bower_components/toastr/toastr.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page" style="background: url('<?php echo base_url() ?>public/admin/img/background.jpg') center center no-repeat; background-size: cover;overflow: hidden;">
<div class="login-box">
    <div class="login-logo">
        <img src="<?php echo base_url() ?>public/admin/img/logo.jpg">
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"><?php echo lang('login_heading');?></p>
        <?php echo form_open();?>
        <div class="form-group has-feedback">
            <?php echo form_input('identity',set_value('identity'),['class'=>'form-control', 'placeholder'=> lang('login_identity_label')]);?>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <span class="text-danger"><?php echo form_error('identity') ?></span>
        <div class="form-group has-feedback">
            <?php echo form_password('password','',['class'=>'form-control', 'placeholder'=> lang('login_password_label')]);?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <span class="text-danger"><?php echo form_error('password') ?></span>
        <div class="row">
            <!-- /.col -->
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block btn-flat"><?php echo lang('login_submit_btn') ?></button>
            </div>
            <!-- /.col -->
        </div>
        <?php echo form_close() ?>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?php echo base_url() ?>/public/admin/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url() ?>/public/admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url() ?>/public/admin/bower_components/toastr/toastr.min.js"></script>
<script>
    $(document).ready(function() {
        toastr.options.escapeHtml = true;
        toastr.options.closeButton = true;
        toastr.options.positionClass = "toast-bottom-right";
        toastr.options.timeOut = 5000;
        toastr.options.showMethod = 'fadeIn';
        toastr.options.hideMethod = 'fadeOut';
        <?php if(!empty($this->session->flashdata('message'))): $message = $this->session->flashdata('message'); ?>
        console.log('<?php echo json_encode($message) ?>');
        toastr.<?php echo $message['type']; ?>('<?php echo trim(strip_tags($message['message'])); ?>');
        <?php endif; ?>
    });
</script>
</body>
</html>