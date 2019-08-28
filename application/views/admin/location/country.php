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
                                <th>Tên quốc gia</th>
                                <th>Tên loại tiền</th>
                                <th>Mã tiền</th>
                                <th>Ký hiệu</th>
                                <th>Quy đổi sang VND</th>
                                <th>Trạng thái</th>
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
            <div class="modal-body form">
                <?php echo form_open('', ['id' => 'form', 'class' => '']) ?>
                <input type="hidden" name="id" value="0">
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tên quốc gia</label>
                            <input name="title" placeholder="Tên quốc gia" class="form-control" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>Tên loại tiền</label>
                            <input name="currency_name" placeholder="Tên loại tiền" class="form-control" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>Mã tiền</label>
                            <input name="currency_code" placeholder="Mã tiền" class="form-control" type="text"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Ký hiêu</label>
                            <input name="currency_symbol" placeholder="Ký hiệu" class="form-control" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>Chuyển đổi VND</label>
                            <input name="format" placeholder="Chuyển đổi VND" class="form-control" type="text"/>
                        </div>
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select name="status" class="form-control">
                                <option value="1">Hiển thị</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>
                    </div>
                </div>
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
    let url_ajax_import_excel = '<?php if(!empty($this->session->location)) echo site_url('admin/location/ajax_import_excel_').$this->session->location ?>';
    let location_type = 'country';
</script>