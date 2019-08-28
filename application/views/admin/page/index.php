<?php

defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <?php $this->load->view($this->template_path."_block/where_datatables") ?>
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
                        <input type="hidden" value="0" name="msg" />
                        <table id="data-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="select_all" value="1" id="data-table-select-all"></th>
                                <th>ID</th>
                                <th><?php echo lang('text_title');?></th>
                                <th><?php echo lang('text_status');?></th>
                                <th><?php echo lang('text_created_time');?></th>
                                <th><?php echo lang('text_updated_time');?></th>
                                <th><?php echo lang('text_action');?></th>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="title-form"><?php echo lang('heading_title_add');?></h3>
            </div>
            <div class="modal-body form">
                <?php echo form_open('',['id'=>'form','class'=>'']) ?>
                <input type="hidden" name="id" value="0">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_language" data-toggle="tab"><?php echo lang('tab_language');?></a></li>
                        <li><a href="#tab_info" data-toggle="tab"><?php echo lang('tab_info');?></a></li>
                        <li><a href="#tab_image" data-toggle="tab"><?php echo lang('tab_image');?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_language">
                            <ul class="nav nav-pills">
                                <?php if(!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name){ ?>
                                    <li<?php echo ($lang_code == 'vi') ? ' class="active"' : '';?>><a href="#tab_<?php echo $lang_code;?>" data-toggle="tab"><?php echo $lang_name;?></a></li>
                                <?php } ?>
                            </ul>
                            <div class="tab-content">
                                <?php if(!empty($this->config->item('cms_language')))  foreach ($this->config->item('cms_language') as $lang_code => $lang_name){ ?>
                                    <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : '';?>" id="tab_<?php echo $lang_code;?>">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo lang('form_title');?></label>
                                                        <input id="title_<?php echo $lang_code;?>" name="title[<?php echo $lang_code;?>]" placeholder="<?php echo lang('form_title');?>" class="form-control" type="text" />
                                                    </div>

                                                    <div class="form-group">
                                                        <label><?php echo lang('from_content');?></label>
                                                        <textarea id="content_<?php echo $lang_code;?>" name="content[<?php echo $lang_code;?>]" class="tinymce form-control" rows="10"></textarea>
                                                    </div>
                                                      <div class="form-group">
                                                        <label><?php echo lang('from_content');?></label>
                                                        <textarea id="content_more_<?php echo $lang_code;?>" name="content_more[<?php echo $lang_code;?>]" class="tinymce form-control" rows="10"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <?php $this->load->view($this->template_path.'_block/seo_meta',['lang_code'=>$lang_code]) ?>
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
                                    <label>Layout style</label>
                                    <input name="style" placeholder="Layout style" class="form-control" type="text" />
                                </div>

                                <div class="form-group">
                                    <label><?php echo lang('form_status');?></label>
                                    <select class="form-control" name="is_status">
                                        <option value="0"><?php echo lang('text_status_0');?></option>
                                        <option value="1" selected><?php echo lang('text_status_1');?></option>
                                        <option value="2"><?php echo lang('text_status_2');?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_image">
                            <div class="box-body">
                                <?php $this->load->view($this->template_path. '_block/input_media') ?>
                                <div class="form-group">
                                    <label>Banner</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-addon" onclick="chooseImage('form_thumbnail1')"><i class="fa fa-fw fa-image"></i><?php echo lang('btn_select_image');?></span>
                                        <input id="form_thumbnail1" onclick="chooseImage('form_thumbnail1')" name="banner" placeholder="Banner" class="form-control" type="text" value=""/>
                                        <span class="input-group-addon" style="padding: 0;"><a href="<?php echo getImageThumb(); ?>" class="fancybox"><img src="<?php echo getImageThumb(); ?>" width="44" height="44"></a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary pull-left"><?php echo lang('btn_save');?></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('btn_cancel');?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
<script>
    var url_ajax_load_category = '<?php echo site_url('admin/category/ajax_load') ?>';
    var url_ajax_load = '<?php echo site_url('admin/category/ajax_load') ?>';
</script>