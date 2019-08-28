<?php
defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <?php $this->load->view($this->template_path."_block/where_datatables") ?>
                    <?php $this->load->view($this->template_path."_block/button") ?>
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
                            <th><?php echo lang('text_link');?></th>
                            <th><?php echo lang('text_review');?></th>
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
                            <label><?php echo lang('text_link');?>*</label>
                            <input name="link_video" placeholder="<?php echo lang('text_link');?>" class="form-control" type="text" />
                        </div>
                        <div class="col-md-6">
                            <label>Trạng thái</label>
                            <select class="form-control" name="status">
                                <option value="1" selected="">Hiển thị</option>
                                <option value="0">Hủy</option>
                            </select>
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
<div class="modal fade" id="modal_view" role="dialog">
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
                    <iframe width="700" height="420" name="view" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('btn_cancel');?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
<script>
    var url_ajax_view = '<?php echo site_url('admin/video/ajax_view')?>'

</script>   