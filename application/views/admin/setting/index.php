<section class="content">
    <?php echo form_open("admin/setting"); ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6 text-right">
                        <button class="btn btn-success" type="submit">
                            <i class="glyphicon glyphicon-plus"></i> <?php echo lang('btn_save'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_general" data-toggle="tab">Tổng quan</a></li>
                    <li><a href="#tab_social" data-toggle="tab">Social</a></li>
                    <li><a href="#tab_contact" data-toggle="tab">Thông tin liên hệ</a></li>
                    <li><a href="#tab_homepage" data-toggle="tab">Trang chủ</a></li>
                    <li><a href="#tab_system" data-toggle="tab"><?php echo lang('tab_system'); ?></a></li>
                </ul>
                <div class="tab-content">
                    <?php $this->load->view($this->template_path.'setting/tab_general') ?>
                    <?php $this->load->view($this->template_path.'setting/tab_system') ?>
                    <?php $this->load->view($this->template_path.'setting/tab_homepage') ?>
                    <?php $this->load->view($this->template_path.'setting/tab_social') ?>
                    <?php $this->load->view($this->template_path.'setting/tab_contact') ?>
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <?php echo form_close(); ?>
</section>
<!-- /.content-wrapper -->
<script>
    var url_ajax_backup_db = '<?php echo site_url('admin/setting/ajax_backup_db')?>',
    url_ajax_restore_db = '<?php echo site_url('admin/setting/ajax_restore_db')?>',
    url_ajax_delete_db = '<?php echo site_url('admin/setting/ajax_delete_db')?>'
    ;
</script>
