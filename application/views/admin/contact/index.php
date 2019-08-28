<?php

defined('BASEPATH') OR exit('No direct script access allowed');
$controller = $this->router->fetch_class();
$method = $this->router->fetch_method();
?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <?php $this->load->view($this->template_path . "_block/where_datatables") ?>
                    <div class="col-sm-5 col-xs-12 text-right">
                        <?php echo button_admin() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <form action="" id="form-table" method="post">
                        <input type="hidden" value="0" name="msg"/>
                        <table id="data-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="select_all" value="1" id="data-table-select-all"></th>
                                <th>ID</th>
                                <th><?php echo lang('text_fullname'); ?></th>
                                <th><?php echo lang('text_phone'); ?></th>
                                <th><?php echo lang('text_email'); ?></th>
                                <th><?php echo lang('text_created_time'); ?></th>
                                <th><?php echo lang('text_action'); ?></th>
                            </tr>
                            </thead>
                        </table>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->


<div class="modal fade view_contact" id="modal_form" role="dialog">
    <div class="modal-dialog" style="width: 100%; max-width: 850px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="title-form">Chi tiết </h3>
            </div>
            <div class="modal-body" style="padding-right: 15px;padding-left: 15px;">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Họ và tên</label>
                            <input id="fullname" class="form-control" type="text" readonly="">
                        </div>
                        
                        <div class="form-group">
                            <label>Email</label>
                            <input id="email" class="form-control" readonly="">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input id="phone" class="form-control" readonly="">
                        </div>
                        <div class="form-group">
                            <label>Ngày liên hệ</label>
                            <input id="created_time" class="form-control" readonly="">
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label>Phản hồi</label>
                            <textarea id="content" class=" form-control" rows="7" readonly=""></textarea>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
<script>
    var url_ajax_view = '<?php echo site_url('admin/contact/ajax_view')?>'

</script>