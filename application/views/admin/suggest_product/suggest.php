<?php

defined('BASEPATH') or exit('No direct script access allowed');
$controller = $this->router->fetch_class();
// dd($controller);
$method = $this->router->fetch_method();
?>
<style>
    table tr td {
        vertical-align: middle !important;
    }

    .portlet > .portlet-body {
        clear: both;
    }

    .col-left, .col-right {
        padding: 5px;
        border: 1px solid #E5E5E5;
        width: 48%;
        height: 368px;
        float: left;
    }

    .col-title {
        padding-bottom: 5px;
        border-bottom: 1px dotted #E5E5E5;
    }

    .col-title input[type='text'] {
        width: 90%;
        float: left;
        margin-right: 5px;
        height: 30px;
    }

    .green.btn {
        color: white;
        background-color: #35aa47;
        padding: 4px 14px;
    }

    .scrollbar {
        overflow-y: auto;
        height: 250px;
        margin: 10px 0px;
    }

    .col-left ul, .col-left ul li, .col-right ul, .col-right ul li {
        margin: 0px;
        padding: 0px;
        list-style: none;
    }

    .col-left ul li.select-product, .col-right ul li.select-product {
        width: 100%;
        float: left;
        border-bottom: 1px dotted #eee;
        padding-bottom: 5px;
        margin-bottom: 5px;
        cursor: pointer;
    }

    .col-center {
        float: left;
        width: 4%;
        height: 368px;
        background: url(<?php echo base_url('public/admin/img/switch.png')?>) no-repeat center center;
    }

    .col-left ul li .imgs, .col-right ul li .imgs {
        border: 1px solid #eee;
        display: block;
        width: 50px;
        height: 50px;
        position: relative;
        float: left;
        margin-right: 5px;
    }
</style>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body" style="display: flex;">
                    <div class="col-sm-7"></div>
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
                        <input type="hidden" value="0" name="msg"/>
                        <table id="data-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="select_all" value="1" id="data-table-select-all"></th>
                                <th>ID</th>
                                <th>Ngày hiển thị</th>
                                <th><?php echo lang('text_created_time'); ?></th>
                                <th><?php echo lang('text_updated_time'); ?></th>
                                <th><?php echo lang('text_action'); ?></th>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="title-form"><?php echo lang('heading_title_add'); ?></h3>
            </div>
            <div class="modal-body form">
                <?php echo form_open('', ['id' => 'form', 'class' => '']) ?>
                <input type="hidden" name="id" value="0">
                <!-- Custom Tabs -->
                <div class="box-body" style="padding-bottom: 60px; padding-right: 20px; padding-left: 20px;">
                    <div class="row">
                        <input type="hidden" name="id" class="form-control pull-right" value="">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Ngày hiển thị:</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="displayed_time" class="form-control pull-right" id="datepicker">
                                </div>
                            </div>
                        </div>
                        <fieldset class="form-group album-contain">
                            <legend>Hôm nay mua gì</legend>
                            <div class="portlet light bordered" id="block-related">
                                <div class="portlet-body form" style="">

                                    <div class="form-group" style="padding-bottom: 30px;">
                                        <div class="col-md-6">
                                            <select class="form-control select2 filter_category"
                                                    title="filter_category_id" name="category_id"
                                                    style="width: 100%;" tabindex="-1" aria-hidden="true">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top:10px;height: 367px;">
                                        <div class="col-md-12">
                                            <div class="col-left">
                                                <div class="col-title">
                                                    <input type="text" class="form-control" id="text-search-product"
                                                           placeholder="Tìm theo tên sản phẩm">
                                                    <a class="btn green btn_search" id="button-search-product"
                                                       data-lang="vi">
                                                        <i class="fa fa-search"></i>
                                                        Tìm
                                                    </a>
                                                </div>
                                                <div class="scrollbar" id="left-product">
                                                    <ul>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-center"></div>
                                            <div class="col-right">
                                                <div class="nav-tabs-custom">
                                                    <ul class="nav nav-tabs">
                                                        <?php if (!empty($category)) foreach ($category as $key => $value): ?>
                                                            <li class="<?php echo $key == 0 ? 'active' : '' ?>"><a
                                                                        href="#tab_<?php echo $value->id ?>"
                                                                        data-toggle="tab"
                                                                        aria-expanded="true"><?php echo $value->title ?></a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <?php if (!empty($category)) foreach ($category as $key => $value): ?>
                                                            <div class="tab-pane <?php echo $key == 0 ? 'active' : '' ?>"
                                                                 data-id="<?php echo $value->id ?>"
                                                                 id="tab_<?php echo $value->id ?>">
                                                                <div class="scrollbar">
                                                                    <ul>

                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="product_related" id="product-related" value="">
                                            <div id="product-current" data-id="3643"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <!-- nav-tabs-custom -->
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer">
                <!-- window.location.reload() -->
                <button type="button" id="btnSave" onclick="save();"
                        class="btn btn-primary pull-left"><?php echo lang('btn_save'); ?></button>
                <button type="button" class="btn btn-danger"
                        data-dismiss="modal"><?php echo lang('btn_cancel'); ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->


<script>
    var available_language = '<?php if (!empty($this->config->item('cms_language'))) echo json_encode($this->config->item('cms_language'));
    else null ?>';
    var rootpath = '<?php echo MEDIA_PATH ?>';
    var url_ajax_load_category = '<?php echo site_url('admin/suggest_product/ajax_load_category') ?>';
    var url_ajax_load_product = '<?php echo site_url('admin/suggest_product/ajax_load_product')?>'
    var type = '<?php echo $this->session->type ?>';
</script>