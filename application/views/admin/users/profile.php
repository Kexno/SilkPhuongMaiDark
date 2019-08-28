<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Main content -->
<section class="content">
    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
        <?php echo form_open();?>
        <div class="box-body">
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-6">
                        <label><?php echo lang('form_username');?></label>
                        <input name="username" placeholder="<?php echo lang('form_username');?>" class="form-control" type="text" disabled="disabled" value="<?php echo $data['username'];?>" />
                        <span class="text-danger"><?php echo form_error('username') ?></span>
                    </div>
                    <div class="col-xs-6">
                        <label><?php echo lang('form_email');?></label>
                        <input name="email" placeholder="<?php echo lang('form_email');?>" class="form-control" type="text" disabled="disabled" value="<?php echo $data['email'];?>" />
                        <span class="text-danger"><?php echo form_error('email') ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-6">
                        <label><?php echo lang('form_last_name');?></label>
                        <input name="last_name" placeholder="<?php echo lang('form_last_name');?>" class="form-control" type="text" value="<?php echo $data['last_name'];?>" />
                        <span class="text-danger"><?php echo form_error('last_name') ?></span>
                    </div>
                    <div class="col-xs-6">
                        <label><?php echo lang('form_first_name');?></label>
                        <input name="first_name" placeholder="<?php echo lang('form_first_name');?>" class="form-control" type="text" value="<?php echo $data['first_name'];?>" />
                        <span class="text-danger"><?php echo form_error('first_name') ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-6">
                        <label><?php echo lang('form_company');?></label>
                        <input name="company" placeholder="<?php echo lang('form_company');?>" class="form-control" type="text" value="<?php echo $data['company'];?>" />
                        <span class="text-danger"><?php echo form_error('company') ?></span>
                    </div>
                    <div class="col-xs-6">
                        <label><?php echo lang('form_phone');?></label>
                        <input name="phone" placeholder="<?php echo lang('form_phone');?>" class="form-control" type="text" value="<?php echo $data['phone'];?>" />
                        <span class="text-danger"><?php echo form_error('phone') ?></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group" id="div-password">
                    <div class="col-xs-6">
                        <label><?php echo lang('form_password');?></label>
                        <input name="password" placeholder="<?php echo lang('form_password');?>" class="form-control" type="password" />
                        <span class="text-danger"><?php echo form_error('password') ?></span>
                    </div>
                    <div class="col-xs-6">
                        <label><?php echo lang('form_repassword');?></label>
                        <input name="repassword" placeholder="<?php echo lang('form_repassword');?>" class="form-control" type="password" />
                        <span class="text-danger"><?php echo form_error('repassword') ?></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary"><?php echo lang('btn_save');?></button> <?php  if(!empty($message)): ?><span style="text-<?php echo $message['type'] === 'error' ? 'danger' : 'success' ?>"><?php echo $message['message'] ?></span><?php endif; ?>
        </div>
        <?php echo form_close();?>
    </div>
    <!-- /.box -->
</section>
<!-- /.content -->
