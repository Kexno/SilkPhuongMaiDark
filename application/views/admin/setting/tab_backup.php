<div class="tab-pane" id="tab_backup">
    <div class="box-body">
        <div class="form-group">
            <label>Create backup database</label>
            <button type="button" class="btn btn-primary btn-ajax-generate-db"> Generate <i class="fa fa-spinner fa-spin" style="display: none"></i> </button>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Danh sách phiên bản backup</h3>
            </div>

            <div class="alert alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <div class="content-msg"></div>
            </div>

            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table id="datatable-database" class="table table-condensed">
                    <tbody><tr>
                        <th class="text-center" style="width: 10px">STT</th>
                        <th class="text-center">Tên file</th>
                        <th class="text-center">Size</th>
                        <th class="text-center">Ngày backup</th>
                        <th class="text-center" style="width: 230px">Action</th>
                    </tr>
                    <?php if(!empty($list_db)) foreach ($list_db as $j => $db): ?>
                        <tr class="text-center">
                            <td><?php echo $j+1 ?></td>
                            <td class="file-name"><?php echo $db['name'] ?></td>
                            <td><?php echo number_format($db['size']) ?> KB</td>
                            <td>
                                <?php echo date('H:i:s d/m/Y',$db['date']) ?>
                            </td>
                            <td>
                                <a href="<?php echo site_url('admin/setting/downloadFile?file='.$db['name']) ?>" class="btn btn-default btn-sm">Download </a>
                                <!--                                                    <a class="btn btn-primary btn-sm btn-ajax-restore-db">Restore <i class="fa fa-spinner fa-spin" style="display: none"></i> </a>-->
                                <a class="btn btn-danger btn-sm btn-ajax-delete-db">Delete <i class="fa fa-spinner fa-spin" style="display: none"></i> </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody></table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>