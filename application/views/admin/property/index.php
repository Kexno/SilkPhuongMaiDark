<?php

defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">

</style>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <?php $this->load->view($this->template_path . "_block/where_datatables") ?>
                    <div class="col-sm-5 col-xs-12 text-right">
                        <?php echo $this->session->property_type=='banner'?button_admin(['add']):button_admin(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="data-table" class="table table-bordered table-hover dataTable" role="grid">
                        <thead>
                            <tr>
                                <th><input type="checkbox" name="select_all" value="1" id="data-table-select-all"></th>
                                <th>ID</th>
                                <th><?php echo lang('text_title'); ?></th>
                                <?php if (!empty($property_type) && $property_type == 'color'): ?>
                                    <th><?php echo lang('text_color_code'); ?></th>
                                    <th><?php echo lang('text_color_hex'); ?></th>
                                <?php endif; ?>
                                <?php if (!empty($property_type) && $property_type == 'import'): ?>
                                    <th><?php echo lang('text_sort') ?></th>
                                    <th><?php echo lang('text_time') ?></th>
                                <?php endif; ?>
                                <th><?php echo lang('text_status'); ?></th>
                                <th><?php echo lang('text_created_time'); ?></th>
                                <th><?php echo lang('text_updated_time'); ?></th>
                                <th><?php echo lang('text_action'); ?></th>
                            </tr>
                        </thead>
                    </table>
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
    <div class="modal-dialog" style="width: 60%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="title-form"><?php echo lang('heading_title_add'); ?></h3>
            </div>
            <div class="modal-body form">
                <?php echo form_open('', ['id' => 'form', 'class' => '']) ?>
                <input type="hidden" name="id" value="0">
                <input type="hidden" name="type" value="<?php echo !empty($property_type) ? $property_type : '' ?>">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_language"
                          data-toggle="tab"><?php echo lang('tab_language'); ?></a></li>
                        <?php if (!empty($property_type) && $property_type == 'import'): ?>
                           <li><a href="#tab_info" data-toggle="tab"><?php echo lang('tab_info'); ?></a></li>
                        <?php endif; ?>
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
                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <label><?php echo lang('form_title'); ?></label>
                                                    <input id="title_<?php echo $lang_code; ?>"
                                                    name="title[<?php echo $lang_code; ?>]"
                                                    placeholder="<?php echo lang('form_title'); ?>"
                                                    class="form-control" type="text"/>
                                                </div>
                                                <?php if (!empty($property_type) && $property_type != 'import'): ?>
                                                    <div class="form-group">
                                                        <label><?php echo lang('form_description'); ?></label>
                                                        <textarea id="description_<?php echo $lang_code; ?>"
                                                          name="description[<?php echo $lang_code; ?>]"
                                                          placeholder="<?php echo lang('form_description'); ?>"
                                                          class="form-control"></textarea>
                                                      </div>
                                                      <?php else: ?>
                                                        <div class="form-group">
                                                            <label><?php echo lang('text_time'); ?></label>
                                                            <input id="time_<?php echo $lang_code; ?>"
                                                            name="time[<?php echo $lang_code; ?>]"
                                                            placeholder="<?php echo lang('text_time'); ?>"
                                                            class="form-control" type="text"/>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                         <div class="tab-pane" id="tab_info">
                            <div class="box-body">
                                <?php if (!empty($property_type) && $property_type === 'genre'): ?>
                                    <div class="form-group">
                                        <label>Chọn danh mục</label>
                                        <select class="form-control select2" data-placeholder="Chọn danh mục"
                                                name="category_id" style="width: 100%;" tabindex="-1"
                                                aria-hidden="true"></select>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($property_type) && $property_type === 'color'): ?>
                                    <div class="form-group">
                                        <label>Mã màu sắc</label>
                                        <input name="color_code" id="color_code" placeholder="Mã màu sắc"
                                               class="form-control" type="text"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Chọn màu</label>
                                        <div class="input-group my-colorpicker2">
                                            <input type="text" class="form-control my-colorpicker2" name="color_hex"
                                                   id="color_hex" placeholder="Chọn màu sắc">
                                            <div class="input-group-addon">
                                                <i></i>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($property_type) && $property_type == 'import'): ?>
                                <div class="form-group">
                                    <label>Sắp xếp</label>
                                    <input name="order" id="color_code" placeholder="Sắp xếp"
                                           class="form-control" type="text"/>
                                </div>
                                <?php endif; ?>
<!--                                --><?php //if (!empty($property_type) && $property_type != 'import') {
//                                    $this->load->view($this->template_path . '_block/input_media');
//                                } ?>
<!--                                <div class="form-group">-->
<!--                                    <label>--><?php //echo lang('form_status'); ?><!--</label>-->
<!--                                    <select class="form-control" name="is_status">-->
<!--                                        <option value="0">--><?php //echo lang('text_status_0'); ?><!--</option>-->
<!--                                        <option value="1" selected>--><?php //echo lang('text_status_1'); ?><!--</option>-->
<!--                                    </select>-->
<!--                                </div>-->
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
<script type="text/javascript">
    var property_type = '<?php echo !empty($property_type) ? $property_type : '' ?>';
    url_ajax_load_category = '<?php echo base_url('admin/category/ajax_load_lv1/product') ?>';
</script>