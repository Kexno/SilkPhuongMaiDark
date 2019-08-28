<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                  <div class="col-xs-6" style="padding: 0 5px">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                      <select class="form-control select2 filter_city_id" title="filter_city_id" name="filter_city_id"
                              style="width: 100%;" tabindex="-1" aria-hidden="true">
                        <option value="0">Thành phố</option>
                      </select>
                    </div>
                  </div>
                    <div class="col-sm-6 col-xs-12 text-right">
                        <button class="btn btn-success" onclick="add_form()">
                            <i class="glyphicon glyphicon-plus"></i> <?php echo lang('btn_add');?>
                        </button>
                        <button class="btn btn-danger" onclick="delete_multiple()">
                            <i class="glyphicon glyphicon-trash"></i> <?php echo lang('btn_remove');?>
                        </button>
                        <button class="btn btn-default" onclick="reload_table()">
                            <i class="glyphicon glyphicon-refresh"></i> <?php echo lang('btn_reload'); ?>
                        </button>
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
                                <th><?php echo lang('text_title'); ?></th>
                                <th>Tỉnh/Thành phố</th>
                                <th>Loại</th>
                                <th>Địa chỉ đầy đủ</th>
                                <th>Vĩ độ</th>
                                <th>Kinh độ</th>
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
                                    <label>Tỉnh / Thành phố</label>
                                    <select class="form-control select2 city_" name="city_id" style="width: 100%;" tabindex="-1" aria-hidden="true"></select>
                                </div>
                                
                                <div class="form-group">
                                    <label>Địa chỉ đầy đủ</label>
                                    <input id="name_with_type" name="name_with_type" class="form-control" placeholder="Địa chỉ đầy đủ">
                                </div>

                                
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <a class="btn btn-default" onclick="show_modal_maps()">
                                        <i class="fa fa-map"></i> Tọa độ</i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Vĩ độ</label>
                                    <input id="latitude" name="latitude" class="form-control" placeholder="Vĩ độ">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Kinh độ</label>
                                    <input id="longitude" name="longitude" class="form-control" placeholder="Kinh độ">
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
    var url_ajax_import_excel = '<?php echo site_url('admin/location/ajax_import_excel_district') ?>';
    var location_type='district';
    var url_ajax_city = '<?php echo site_url('admin/location/ajax_load_city') ?>';
</script>