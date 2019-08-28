<?php

defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="card card-default" style="border: solid 1px #337ab7;">
                            <div class="card-header bg-primary" style="padding: 10px; font-size: 20px;">Add Item</div>
                            <div class="card-body" style="padding: 10px;">
                                <form id="frmEdit" class="form-horizontal">
                                    <input type="hidden" name="id" class="item-menu">
                                    <div class="form-group">
                                        <label for="text" class="col-sm-2 control-label">Text</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control item-menu" name="text" id="text" placeholder="Text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="text" class="col-sm-2 control-label">Icon</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <input type="text" class="form-control item-menu" name="icon" id="icon" placeholder="Icon">
                                                <div class="input-group-btn">
                                                    <button type="button" id="myEditor_icon" class="btn btn-default iconpicker" role="iconpicker" data-iconset="fontawesome">
                                                        <i class="empty"></i>
                                                        <input type="hidden" value="empty">
                                                        <span class="caret"></span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="href" class="col-sm-2 control-label">URL</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control item-menu" id="href" name="href" placeholder="URL">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="target" class="col-sm-2 control-label">Target</label>
                                        <div class="col-sm-10">
                                            <select name="target" id="target" class="form-control item-menu">
                                                <option value="_self">Self</option>
                                                <option value="_blank">Blank</option>
                                                <option value="_top">Top</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="target" class="col-sm-2 control-label"><?php echo lang('form_parent');?></label>
                                        <div class="col-sm-10">
                                            <select data-placeholder="Select your option" class="form-control select2 item-menu" id="parent_id" name="parent_id"  style="width: 100%;" tabindex="-1" aria-hidden="true"></select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="target" class="col-sm-2 control-label">Class</label>
                                        <div class="col-sm-10">
                                            <select name="class" id="class" class="form-control item-menu">
                                                <option value="">None</option>
                                                <option value="treeview">treeview</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="href" class="col-sm-2 control-label">Order</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control item-menu" id="order" name="order" placeholder="Order">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer clearfix" style="padding: 10px;">
                                <div class="pull-right">
                                    <button type="button" id="btnUpdate" class="btn btn-primary" disabled><i class="fa fa-refresh"></i> Update</button>
                                    <button type="button" id="btnAdd" class="btn btn-success"><i class="fa fa-plus"></i> Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading clearfix"><h5 class="pull-left">Menu</h5>
                            </div>
                            <div class="panel-body" id="cont">
                                <ul id="myEditor" class="sortableLists list-group">
                                </ul>
                            </div>
                        </div>
                    </div>
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
<script>
    let url_ajax_load_parent_menu = '<?php echo site_url('admin/system_menu/ajax_load_parent_menu') ?>';
    let url_ajax_get_parent_menu = '<?php echo site_url('admin/system_menu/ajax_get_parent_menu') ?>';
</script>
