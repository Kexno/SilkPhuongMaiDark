<?php

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
  .btn-success{
    display: none;
  }
  .modal-content{
    height: auto;
  }
</style>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-body">
          <div class="col-sm-7 col-xs-12">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                <select class="form-control select2 is_status" name="is_status"
                style="width: 100%;" tabindex="-1" aria-hidden="true">
                <option value="">Lựa chọn trạng thái</option>
                <option value="1">Đang hoạt động</option>
                <option value="0">Ngừng hoạt động</option>
              </select>
            </div>
          </div>
        </div>
        
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
              <th><?php echo lang('text_id'); ?></th>
              <th>Tên thành viên</th>
              <th>Phone</th>
              <th><?php echo lang('text_email'); ?></th>
              <th>Giới tính</th>
              <th>Ngày đăng ký</th>
              <th><?php echo lang('text_status'); ?></th>
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
  <div class="modal-dialog" style="width: 70%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
          aria-hidden="true">&times;</span></button>
          <h3 class="modal-title" id="title-form"><?php echo lang('heading_title_add'); ?></h3>
        </div>
        <div class="modal-body form">
          <?php echo form_open('', array('id' => 'form', 'class' => 'form-horizontal')) ?>
          <input type="hidden" name="id" value="0">
          <div class="box-body">
            <div class="form-group">
              <div class="col-xs-6">
                <label>Email</label>
                <input name="email" placeholder="Email" class="form-control" type="text"/>
              </div>
              <div class="col-xs-6">
                <label>Ngày sinh</label>
                <input name="birthday" placeholder="Ngày sinh" class="form-control" type="text"/>
              </div>
            </div>
            <div class="form-group">
              <div class="col-xs-6">
                <label>Họ và tên</label>
                <input name="full_name" placeholder="Họ và tên" class="form-control" type="text"/>
              </div>
              <div class="col-xs-6">
                <label>Địa chỉ</label>
                <input name="address" placeholder="Địa chỉ" class="form-control" type="text"/>
              </div>
              <div class="col-xs-6">
                <label>Nghề nghiệp</label>
                <input name="job" placeholder="Nghề nghiệp" class="form-control" type="text"/>
              </div>
            </div>

            <div class="form-group">
              <div class="col-xs-6">
                <label>Giới tính</label>
                <select class="form-control" name="gender">
                  <option value="1">Nam</option>
                  <option value="2">Nữ</option>
                  <option value="3">Khác</option>
                </select>
              </div>
              <div class="col-xs-6">
                <label>Số điện thoại</label>
                <input name="phone" placeholder="Số điện thoại liên hệ" class="form-control" type="text"/>
              </div>
            </div>
            <div class="form-group" id="div-password">
              <div class="col-xs-6">
                <label>Password</label>
                <input name="password" placeholder="Password" class="form-control" type="password"/>
              </div>
              <div class="col-xs-6">
                <label>Xác nhận lại password</label>
                <input name="repassword" placeholder="Nhập lại password" class="form-control"
                type="password"/>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-12">
                <div class="input-group input-group-lg">
                  <span class="input-group-addon" onclick="chooseImage('avatar')"><i
                    class="fa fa-fw fa-image"></i><?php echo lang('btn_select_image'); ?></span>
                    <input id="avatar" onclick="chooseImage('avatar')" name="avatar"
                    placeholder="<?php echo lang('avatar'); ?>" class="form-control" type="text"/>
                    <span class="input-group-addon" style="padding: 0;"><a href="<?php echo getImageThumb(); ?>"
                     class="fancybox"><img
                     src="<?php echo getImageThumb(); ?>" width="44" height="44"></a></span>
                   </div>
                 </div>
               </div>

               <div class="form-group" style="padding-bottom: 50px">
                <div class="col-md-12">
                  <label>Trạng thái</label>
                  <select class="form-control" name="active">
                    <option value="0">Ngừng hoạt động</option>
                    <option value="1" selected="">Đang hoạt động</option>
                    <option value="2">Chờ phê duyệt</option>
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
      var url_ajax_list = '<?php echo site_url("admin/account/ajax_list");?>';
      var url_load_city     = '<?php echo site_url("admin/account/load_city")?>';
      var url_load_district = '<?php echo site_url("admin/account/load_district/")?>';
    </script>
