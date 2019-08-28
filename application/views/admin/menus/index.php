<?php

defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <!-- /.box-userer -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-5 col-xs-12">
                            <div class="box box-default">
                                <div class="box-body">
                                    <div class="form-group">
                                        <select id="menu_locations" class="form-control">
                                            <?php if (!empty($this->config->item('cms_menu'))) foreach ($this->config->item('cms_menu') as $lang => $name): ?>
                                                <option value="<?php echo $lang ?>"><?php echo $name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <select id="menu_languages" class="form-control">
                                            <?php if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang => $name): ?>
                                                <option value="<?php echo $lang ?>"><?php echo $name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <button class="btn btn-primary btnShowMenu">Show Menu <i class="fa fa-spinner fa-spin" style="display: none"></i> </button>
                                </div>
                            </div>

                            <div id="listContent"></div>
                        </div>
                        <div class="col-sm-7 col-xs-12">
                            <menu id="nestable-menu" class="text-center">
                                <button class="btn btn-primary btnSaveMenu" type="button">Save Menu</button>
                                <button class="btn btn-info" type="button" data-action="expand-all">Expand All</button>
                                <button class="btn btn-danger" type="button" data-action="collapse-all">Collapse All</button>
                            </menu>
                            <label id="output-menu" class="label label-info"></label>
                            <div class="dd col-xs-12" id="nestable">
                                <ol class="dd-list"></ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            <!-- /.box-body -->
        </div>
    </div>
    <!-- /.col -->
</section>



<script>
    var url_ajax_load_menu = '<?php echo site_url('admin/menus/ajax_load_menu') ?>';
    var url_ajax_save_menu = '<?php echo site_url('admin/menus/ajax_save_menu') ?>';
</script>
