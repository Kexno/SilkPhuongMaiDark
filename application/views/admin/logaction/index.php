<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="col-sm-7">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                <select class="form-control select2 user_id" name="user_id"
                                style="width: 100%;" tabindex="-1" aria-hidden="true">
                                </select>
                        </div>
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
                            <th>ID</th>
                            <th>Action</th>
                            <th>Note</th>
                            <th>Author</th>
                            <th>Ngày thực hiện</th>
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
<!-- /.content-wrapper -->
<script>
    var url_load_users    = '<?php echo site_url('admin/logaction/load_users') ?>';
</script>