<?php

defined('BASEPATH') OR exit('No direct script access allowed');?>
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
                    <table id="data-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th><input type="checkbox" name="select_all" value="1" id="data-table-select-all"></th>
                            <th><?php echo lang('text_id');?></th>
                            <th><?php echo lang('text_title');?></th>
                            <th><?php echo lang('text_description');?></th>
                            <th><?php echo lang('text_action');?></th>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="title-form"><?php echo lang('heading_title_add');?></h3>
            </div>
            <div class="modal-body form">
                <?php echo form_open('',['id'=>'form','class'=>'form-horizontal']) ?>
                    <input type="hidden" name="id" value="0">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">

                            <li class="active"><a href="#tab_info" data-toggle="tab"><?php echo lang('tab_info');?></a></li>
                            <li><a href="#tab_per" data-toggle="tab"><?php echo lang('tab_per');?></a></li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_info" >
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label>Tên nhóm*</label>
                                            <input name="name" placeholder="Tên nhóm" class="form-control" type="text" />
                                        </div>
                                        <div class="col-xs-6">
                                            <label>Mô tả</label>
                                            <input name="description" placeholder="Mô tả" class="form-control" type="text" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_per">
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <table class="table table-hover" id="tbl_per">
                                                <thead>
                                                <tr>
                                                    <th><?php echo lang('text_category');?></th>
                                                    <th class="text-center"><?php echo lang('text_view');?></th>
                                                    <th class="text-center"><?php echo lang('text_add');?></th>
                                                    <th class="text-center"><?php echo lang('text_edit');?></th>
                                                    <th class="text-center"><?php echo lang('text_delete');?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                if(!empty($controllers)):
                                                    foreach($controllers as $controller):
                                                        $tmpName = pathinfo($controller);
                                                        $name = strtolower($tmpName['filename']);
                                                        if(in_array($name,array('dashboard','auth','notification','navmenu','logaction','migrate','category'))){
                                                            continue;
                                                        }
                                                        ?>
                                                        <tr id="per_<?php echo $name;?>">

                                                            <td><?php echo !empty($this->config->item('cms_language_role')[$name]) ? $this->config->item('cms_language_role')[$name] : ucfirst($name);?></td>
                                                            
                                                            <td class="text-center">
                                                                <input type="checkbox" id="per_<?php echo $name;?>_view" name="permission[<?php echo $name;?>][view]"  value="1" />
                                                            </td>
                                                            <?php if(in_array($name,$this->config->item('cms_check_add'))) : ?>
                                                                <td class="text-center">
                                                                    <input type="checkbox" id="per_<?php echo $name;?>_add" name="permission[<?php echo $name;?>][add]"  value="1" />
                                                                </td>
                                                            <?php else: ?>
                                                                <td></td>
                                                            <?php endif; ?>

                                                            <?php if(in_array($name,$this->config->item('cms_check_edit'))) : ?>
                                                                <td class="text-center">
                                                                    <input type="checkbox" id="per_<?php echo $name;?>_edit" name="permission[<?php echo $name;?>][edit]"  value="1" />
                                                                </td>
                                                            <?php else: ?>
                                                                <td></td>
                                                            <?php endif; ?>

                                                            <?php if(!in_array($name,$this->config->item('cms_check_not_delete'))) : ?>
                                                            <td class="text-center">
                                                                <input type="checkbox" id="per_<?php echo $name;?>_delete" name="permission[<?php echo $name;?>][delete]"  value="1" />
                                                            </td>
                                                            <?php else: ?>
                                                                <td></td>
                                                            <?php endif; ?>

                                                        </tr>
                                                        <?php
                                                    endforeach;
                                                endif;
                                                ?>
                                                </tbody>
                                            </table>
                                            <a href="javascript:;" onclick="check_all_per();">Chọn tất cả</a> /
                                            <a href="javascript:;" onclick="uncheck_all_per();">Bỏ chọn</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    var url_ajax_get_auth='<?php echo site_url("admin/{$this->router->fetch_class()}/ajax_get_auth")?>';
</script>
