<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                  <div class="col-sm-6 col-xs-12"></div>
                    <div class="col-sm-6 col-xs-12 text-right">
                        <button class="btn btn-primary" onclick="import_excel()"  style="display: none">
                            <i class="fa fa-upload"></i> Import <i class="fa fa-spinner fa-spin"
                                                                   style="display: none"></i>
                        </button>
                        <input name="importExcel" type="file"
                               accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                               style="display:none;"/>
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
                                <th>Phường/xã</th>
                                <th>Quận/Huyện</th>
                                <th>Tỉnh/Thành phố</th>
                                <th>Loại</th>
                                <th>Địa chỉ đầy đủ</th>
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
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_language"
                                              data-toggle="tab"><?php echo lang('tab_language'); ?></a></li>
                        <li><a href="#tab_info" data-toggle="tab"><?php echo lang('tab_info'); ?></a></li>
                        <li><a href="#tab_image" data-toggle="tab"><?php echo lang('tab_image'); ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_language">
                            <ul class="nav nav-pills">
                                <?php if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
                                    <li<?php echo ($lang_code == 'vi') ? ' class="active"' : ''; ?>><a
                                            href="#tab_<?php echo $lang_code; ?>"
                                            data-toggle="tab"><?php echo $lang_name; ?></a></li>
                                <?php } ?>
                            </ul>
                            <div class="tab-content">
                                <?php if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
                                    <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>"
                                         id="tab_<?php echo $lang_code; ?>">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo lang('form_title'); ?></label>
                                                        <input id="title_<?php echo $lang_code; ?>"
                                                               name="title[<?php echo $lang_code; ?>]"
                                                               placeholder="<?php echo lang('form_title'); ?>"
                                                               class="form-control" type="text"/>
                                                    </div>

                                                    <div class="form-group">
                                                        <label><?php echo lang('form_description'); ?></label>
                                                        <textarea id="description_<?php echo $lang_code; ?>"
                                                                  name="description[<?php echo $lang_code; ?>]"
                                                                  placeholder="<?php echo lang('form_description'); ?>"
                                                                  class="form-control"></textarea>
                                                    </div>


                                                    <div class="form-group">
                                                        <label><?php echo lang('from_content'); ?></label>
                                                        <textarea id="content_<?php echo $lang_code; ?>"
                                                                  name="content[<?php echo $lang_code; ?>]"
                                                                  placeholder="<?php echo lang('from_content'); ?>"
                                                                  class="tinymce form-control content_post"
                                                                  rows="10"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <?php $this->load->view($this->template_path . '_block/seo_meta', ['lang_code' => $lang_code]) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_info">
                            <div class="box-body">
                                <div class="form-group">
                                    <label><?php echo lang('from_category'); ?></label>
                                    <select title="<?php echo lang('from_category'); ?>" class="form-control select2"
                                            name="category_id[]" style="width: 100%;" tabindex="-1"
                                            aria-hidden="true"></select>
                                </div>
                                <div class="form-group">
                                    <label>Video (Youtube)</label>
                                    <input name="url_video" placeholder="Link video youtube" class="form-control"
                                           type="text"/>
                                </div>

                                <div class="form-group">
                                    <label><?php echo lang('from_date_show'); ?></label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input name="displayed_time" placeholder="<?php echo lang('from_date_show'); ?>"
                                               class="form-control datepicker" type="text"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('form_status'); ?></label>
                                    <select class="form-control" name="is_status">
                                        <option value="0"><?php echo lang('text_status_0'); ?></option>
                                        <option value="1" selected><?php echo lang('text_status_1'); ?></option>
                                        <option value="2"><?php echo lang('text_status_2'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_image">
                            <div class="box-body">
                                <?php $this->load->view($this->template_path . '_block/input_media') ?>
                            </div>
                        </div>
                    </div>

                    <!-- /.tab-content -->
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
    var url_ajax_import_excel = '<?php echo site_url('admin/location/ajax_import_excel_ward') ?>';
</script>