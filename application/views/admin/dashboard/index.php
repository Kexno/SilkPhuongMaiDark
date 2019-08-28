<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3 id="total_post"><i class="fa fa-refresh fa-spin"></i></h3>
                    <p>Tổng số bài viết</p>
                </div>
                <div class="icon">
                    <i class="fa fa-file-text-o"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3 id="total_product"><i class="fa fa-refresh fa-spin"></i></h3>
                    <p>Tổng số sản phẩm</p>
                </div>
                <div class="icon">
                    <i class="fa fa-address-card"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- endchar -->
</section>
<!-- /.content -->
<script>
    var url_ajax_total = '<?php echo site_url("admin/{$this->router->fetch_class()}/ajax_total")?>';
</script>
