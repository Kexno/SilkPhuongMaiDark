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
                            <th><?php echo lang('text_username');?></th>
                            <th><?php echo lang('text_email');?></th>
                            <th><?php echo lang('text_lastname').' & '.lang('text_firstname');?></th></th>
                            <th><?php echo lang('text_status');?></th>
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
    <div class="modal-dialog" style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="title-form"><?php echo lang('heading_title_add');?></h3>
            </div>
            <div class="modal-body form" style="padding: 10px;">
                <?php echo form_open('',['id'=>'form','class'=>'form-horizontal']) ?>
                <input type="hidden" name="id" value="0">
                <div class="box-body">
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label><?php echo lang('form_username');?>*</label>
                            <input name="username" placeholder="<?php echo lang('form_username');?>" class="form-control" type="text" />
                        </div>
                        <div class="col-xs-6">
                            <label><?php echo lang('form_email');?>*</label>
                            <input name="email" placeholder="<?php echo lang('form_email');?>" class="form-control" type="text" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label><?php echo lang('form_last_name');?></label>
                            <input name="last_name" placeholder="<?php echo lang('form_last_name');?>" class="form-control" type="text" />
                        </div>
                        <div class="col-xs-6">
                            <label><?php echo lang('form_first_name');?></label>
                            <input name="first_name" placeholder="<?php echo lang('form_first_name');?>" class="form-control" type="text" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label><?php echo lang('form_company');?></label>
                            <input name="company" placeholder="<?php echo lang('form_company');?>" class="form-control" type="text" />
                        </div>
                        <div class="col-xs-6">
                            <label><?php echo lang('form_phone');?></label>
                            <input name="phone" placeholder="<?php echo lang('form_phone');?>" class="form-control" type="text" />
                        </div>
                    </div>
                    <div class="form-group" id="div-password">
                        <div class="col-xs-6">
                            <label><?php echo lang('form_password');?>*</label>
                            <input name="password" placeholder="<?php echo lang('form_password');?>" class="form-control" type="password" />
                        </div>
                        <div class="col-xs-6">
                            <label><?php echo lang('form_repassword');?>*</label>
                            <input name="repassword" placeholder="<?php echo lang('form_repassword');?>" class="form-control" type="password" />
                        </div>
                    </div>
                    <div class="form-group" id="div-password">
                        <div class="col-md-12">
                            <label>Trạng thái</label>
                            <select class="form-control" name="active">
                                <option value="0">Ngừng hoạt động</option>
                                <option value="1" selected="">Đang hoạt động</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">

                        <div class="col-xs-12">
                            <label>Vai trò</label>
                            <select name="group_id" class="form-control" <?php if($this->session->userdata['user_id']!=1) echo 'disabled';?>>
                                <?php
                                $selected='';
                                if(!empty($group_user)) foreach ($group_user as $item){
                                    echo "<option value='".$item->id."'>".$item->name."</option>";
                                }
                                ?>
                            </select>
                            <?php
                            if($this->session->userdata['user_id']!=1){
                                ?>
                                <input type="hidden" name="group_id" value="">
                                <?php
                            }?>
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