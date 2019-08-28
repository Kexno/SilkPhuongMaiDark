<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="col-sm-7">
                        <div class="form-group">
                            <select class="form-control is_status" name="is_status">
                                <option value="">Lựa chọn trạng thái đơn hàng</option>
                                <option value="1">Đang xử lý</option>
                                <option value="2">Đã xử lý</option>
                                <option value="0">Hủy</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-sm-5 col-xs-12 text-right">
                        <?php echo button_admin() ?>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <table id="data-table" class="table table-responsive table-sm ">
                                <thead>
                                    <tr class="text-center">
                                        <th  ><input type="checkbox" name="select_all" value="1" id="data-table-select-all"></th>
                                        <th width="10%">Giới tính</th>
                                        <th width="10%"><?php echo lang('col_firstname');?></th>
                                        <th>Số điện thoại</th>
                                        <!-- <th>Tổng tiền</th> -->
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th><?php echo lang('col_action');?></th>   
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

        <div class="modal fade" id="modal_form" role="dialog">
            <div class="modal-dialog" style="width: 100%; max-width: 850px">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title" id="title-form">Thông tin đơn hàng</h3>
                    </div>
                    <div class="modal-body form">
                        <form id="form">
                            <input type="hidden" name="id" value="">
                            <table class="table">
                                <tr>
                                    <th><?php echo lang('col_firstname'); ?> :</th>
                                    <td id="full_name"></td>
                                </tr>
                                <tr>
                                    <th>Số điện thoại :</th>
                                    <td id="phone"></td>
                                </tr>
                                <tr>
                                    <th>Địa chỉ nhận hàng :</th>
                                    <td id="address"></td>
                                </tr>
                         <!--        <tr>
                                    <th>Tỉnh / Thành phố :</th>
                                    <td id="city_id"></td>
                                </tr>
                                <tr>
                                    <th>Quận / huyện :</th>
                                    <td id="district_id"></td>
                                </tr>
                                <tr>
                                    <th>Xã/ Phường :</th>
                                    <td id="ward_id"></td>
                                </tr> -->

                                <tr>
                                    <th><?php echo lang('col_created_or_at'); ?> :</th>
                                    <td id="created_time"></td>
                                </tr>
                                <tr>
                                    <th>Trạng thái đơn hàng :</th>
                                    <td>
                                        <select class="form-control" name="is_status" id="is_status">
                                            <option value="">Lựa chọn</option>
                                            <option value="1">Đang xử lý</option>
                                            <option value="2">Đã xử lý</option>
                                            <option value="0">Hủy</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ghi chú :</th>
                                    <td id="note"></td>
                                </tr>
                               <!--  <tr>
                                    <th>Địa chỉ chỉnh sửa :</th>
                                    <td><input type="" name="addredit" class="form-control" /></td>
                                </tr> -->
                            </table>
                        </form>
                        <style>
                            table.list-detail th,table.list-detail tr td{text-align: center}
                        </style>
                        <table class="table list-detail">
                            <thead>
                                <tr style="background:orange;color: #fff">
                                    <th>Tên sản phẩm</th>
                                    <th>Giá</th>
                                </tr>
                            </thead>
                            <tbody id="form_order_products"></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSave" onclick="save()" class="btn btn-primary pull-left"><?php echo lang('btn_save');?></button>
                        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Hủy</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- End Bootstrap modal -->
        <script>
            var url_ajax_list = '<?php echo site_url('admin/order/ajax_list')?>',
            url_ajax_view = '<?php echo site_url('admin/order/ajax_view')?>',
            url_ajax_update = '<?php echo site_url('admin/order/ajax_update')?>',
            url_ajax_add = '<?php echo site_url('admin/order/ajax_add')?>',
            url_ajax_delete = '<?php echo site_url('admin/order/ajax_delete')?>';
            url_ajax_remove_item = '<?php echo site_url('admin/order/ajax_removeItem')?>';
        </script>
        <style>
            .modal-footer-top-button{
                position: unset!important;
            }
            .btn-success{
                display: none;
            }
            .btn-danger{
                display: none;
            }
            .btn-danger.pull-right{
                display: block !important;
            }
        </style>