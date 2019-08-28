        <link href="<?php echo base_url(); ?>public/css//common.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>public/css//home.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>public/css//store.css" rel="stylesheet" type="text/css">
        
        <link href="<?php echo base_url(); ?>public/css//product.css" rel="stylesheet" type="text/css">
        <!-- PAGE CONTENT -->
        <?php if(!empty($banner)){ ?>
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <?php foreach ($banner as $key => $value) { ?>
                        <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $key; ?>" class="<?php echo $key==0?'active':''; ?>"></li>
                    <?php } ?>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <?php foreach ($banner as $key => $value) { ?>
                        <div class="carousel-item <?php echo $key==0?'active':''; ?>" style="background-image: url('<?php echo base_url('public/media/'.$value->thumbnail); ?>')"></div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <div class="kontainer" id="page-content">

            <!-- product categories -->
            
            <div class="row">
                <h3 class="col-12"><?php echo $oneItem->content; ?></h3>
                <p class="col-12"><?php echo $oneItem->content_more; ?></p>
                <?php if(!empty($cate_product)) foreach ($cate_product as $key => $value) { ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="category buttonds">
                        <img src="<?php echo getImageThumb($value->thumbnail,360,240); ?>">
                        <div class="overlay"></div>
                        <div class="content">
                            <div class="row d-flex justify-content-center">
                                <div class="catename col-12"><?php echo $value->title; ?></div>
                                <div class="col-auto"><a href="<?php echo getUrlCateProduct($value); ?>">Xem chi tiết</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            
            <!-- about -->
            <?php if(!empty($about)){ ?>
                <div class="row align-items-center" style="margin-top: 30px;">
                    <img class="col-12 col-lg-6" src="<?php echo base_url('public/media/'.$about->thumbnail); ?>">
                    <div id="infor" class="col-12 col-lg-6">
                        <div class="row justify-content-center">
                            <h4 class="col-12"><?php echo $about->content; ?></h4>
                            <p class="col-12"><?php echo $about->content_more; ?></p>
                            <div class="col-auto"><a href="<?php echo getUrlPage($about); ?>" id="more">Tìm hiểu thêm</a></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- featured products -->
            <?php if(!empty($product_featured)){ ?>
            <div class="row">
                <h3 class="col-12">Sản phẩm nổi bật</h3>
                <?php foreach ($product_featured as $key => $value) { ?>
                <div class="item col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="item-box buttonds">
                        <div class="thumb">
                            <img src="<?php echo getImageThumb($value->thumbnail,360,240); ?>">
                            <!-- <img class="cover-img" src="<?php echo getImageThumb($value->thumbnail,360,240); ?>"> -->
                            <!-- <div class="row">
                                <a class="col-6">
                                    <img src="<?php echo base_url(); ?>public/images//common/expand.svg">
                                </a>
                                <a class="col-6">
                                    <img src="<?php echo base_url(); ?>public/images//common/cart.svg">
                                </a>
                            </div> -->
                        </div>
                        <a href="<?php echo geturlProduct($value); ?>" id="item-name" class="d-flex justify-content-center"><?php echo $value->title; ?></a>
                        <p class="price d-flex justify-content-center">
                            <?php echo formatMoney($value->price); ?><span>&nbsp;đ</span>
                        </p>
                    </div>
                </div>
               <?php } ?> 
            </div>
        <?php } ?>
        </div>
        
        <!-- newspaper -->
        <?php if(!empty($news_feed)){ ?>
            <div class="container-fluid pagepart bluebg textheigh">
                <div class="kontainer">
                    <div class="row">
                        <h1 class="col-12">Truyền thông nói gì về chúng tôi</h1>
                        <?php foreach ($news_feed as $key => $value) { if($key<2){ ?>
                            <div class="col-12 col-md-6" style="margin-bottom: 30px;">
                                <div class="thumbkh">
                                    <a href="<?php echo getUrlNews($value); ?>"><img src="<?php echo getImageThumb($value->thumbnail,720,480); ?>"></a>
                                </div>
                                <p><?php echo $value->description; ?></p>
                            </div>
                        <?php }} ?>
                    </div>
                    <div class="row">
                        <?php foreach ($news_feed as $key => $value) { if($key>=2){ ?>
                            <div class="itembc col-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="itembc-box buttonls">
                                    <div class="thumbbc">
                                         <a href="<?php echo getUrlNews($value); ?>"><img src="<?php echo getImageThumb($value->thumbnail,360,240); ?>"></a>
                                    </div>
                                    <a href="<?php echo getUrlNews($value); ?>" class="itembc-name"><?php echo $value->title; ?></a>
                                    <p class="item-descript"><?php echo $value->description; ?></p>
                                </div>
                            </div>
                        <?php }} ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <script type="text/javascript">
            $('body').addClass('darkbg');
        </script>
