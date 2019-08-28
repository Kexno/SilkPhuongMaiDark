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
                                    <th><?php echo lang('text_id');?></th>
                                    <th><?php echo lang('text_name');?></th>
                                    <th><?php echo lang('text_address');?></th>
                                    <th><?php echo lang('text_phone');?></th></th>
                                    <th><?php echo lang('text_lat');?></th>
                                    <th><?php echo lang('text_long');?></th>
                                    <th><?php echo lang('text_status'); ?></th>
                                    <th style="width: 20%;">Hành động</th>   
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
                                                <div class="col-xs-12">
                                                    <div class="form-group">
                                                        <label><?php echo lang('text_name');?></label>
                                                        <input id="title_<?php echo $lang_code;?>" name="name[<?php echo $lang_code;?>]" placeholder="<?php echo lang('text_name');?>" class="form-control" type="text" />
                                                    </div>

                                                    <div class="form-group">
                                                        <label><?php echo lang('text_address');?></label>
                                                        <input id="description_<?php echo $lang_code;?>" name="address[<?php echo $lang_code;?>]" placeholder="<?php echo lang('text_address');?>" class="form-control" type="text" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_info">
                            <div class="box-body">
                                <label><?php echo lang('text_status');?></label>
                                <select class="form-control" name="is_status">
                                    <option value="1" selected="">Hiển thị</option>
                                    <option value="0">Hủy</option>
                                </select>
                                <div class="form-group">
                                    <label><?php echo lang('text_phone');?></label>
                                    <input id="phone" name="phone" placeholder="<?php echo lang('text_phone');?>" class="form-control" type="text" />
                                </div>
                                <div class="form-group">
                                    <label>Sắp xếp</label>
                                    <input id="order" name="order_asc" placeholder="sắp xếp" class="form-control" type="number" />
                                </div>
                               <!--  <div class="col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <a class="btn btn-default" onclick="show_modal_maps()">
                                            <i class="fa fa-map"></i> Tọa độ</i>
                                        </a>
                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <label><?php echo lang('text_lat');?></label>
                                    <input id="lat" name="lat" placeholder="<?php echo lang('text_lat');?>" class="form-control" type="number" />
                                </div>

                                <div class="form-group">
                                    <label><?php echo lang('text_long');?></label>
                                    <input id="lng" name="lng" placeholder="<?php echo lang('text_long');?>" class="form-control" type="number" />
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

