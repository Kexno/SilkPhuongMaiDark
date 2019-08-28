<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                  <div class="col-sm-7 col-xs-12"></div>
                  <?php $this->load->view($this->template_path."_block/button", ['display_button' => ['import', 'add', 'delete'], 'href' => site_url('admin/location/export_file/').$this->session->location ]) ?>
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
                                
                                <th>Tên tỉnh</th>
                                <th>Mã tỉnh</th>
                                <th>Thứ tự</th>
                                <th>Hành động</th>
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

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog" style="width: 90%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="title-form"><?php echo lang('heading_title_add'); ?></h3>
                </div>
                <div class="modal-body form" style="padding-right: 15px;padding-left: 15px;">
                    <?php echo form_open('', ['id' => 'form', 'class' => '']) ?>
                    <input type="hidden" name="id" value="0">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>Tên</label>
                                        <input id="title" name="title" class="form-control" type="text" placeholder="Tên">
                                    </div>
                                    <div class="form-group">
                                        <label>Loại</label>
                                        <input id="type" name="type" class="form-control" placeholder="Loại">
                                    </div>
                                    <div class="form-group">
                                        <label>Mã tỉnh</label>
                                        <input id="code" name="code" class="form-control" placeholder="Mã tỉnh/thành phố">
                                    </div>
                                    <div class="form-group">
                                        <label>Thứ tự</label>
                                        <input id="order" name="order" class="form-control" placeholder="Thứ tự">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- nav-tabs-custom -->
                    <?php echo form_close() ?>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSave" onclick="save()"
                    class="btn btn-primary pull-left"><?php echo lang('btn_save'); ?></button>
                    <button type="button" class="btn btn-danger"
                    data-dismiss="modal"><?php echo lang('btn_cancel'); ?></button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- End Bootstrap modal -->
    <script>
        var url_ajax_import_excel = '<?php echo site_url('admin/location/ajax_import_excel_city') ?>';
        var location_type='city';
    </script>