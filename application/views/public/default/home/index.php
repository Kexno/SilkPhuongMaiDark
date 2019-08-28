        <!-- banner -->
        
        <div id="banner" class="row justify-content-center align-items-center">
            <img class="col-auto" src="public/images/common/logo-wv-shadow.svg">
        </div>

        <!-- page content-->

        <div class="pagepart container-fluid darkbg textmedium">

            <div class="kontainer" id="story">
                <!-- story -->
                <?php if(!empty($review_home)){ ?>
                    <div class="row justify-content-center">
                        <h1 class="col-12"><?php echo $review_home->content; ?></h1>
                        <?php echo $review_home->content_more; ?>
                    </div>
                <?php } ?>
                <!-- products -->
                <?php $listCate=getListCate(); ?>
                <?php if(!empty($listCate)){ ?>
                    <div class="row align-items-center" id="products" style="margin-top: 30px;">
                        <?php foreach ($listCate as $key => $value) { if($value->parent_id==0){ if($key%2==0){ ?>
                            <img class="col-12 col-lg-6" src="<?php echo base_url('public/media/'.$value->thumbnail); ?>">
                            <div class="col-12 col-lg-6">
                                <div class="row justify-content-center">
                                    <h1 class="col-12"><?php echo $value->title; ?></h1>
                                    <p class="col-12"><?php echo $value->description; ?></p>
                                    <?php foreach ($listCate as $k => $v) { if($v->parent_id==$value->id){ ?>
                                        <a href="<?php echo getUrlCateProduct($v); ?>" class="col-auto bluebg textheigh buttonls"><?php echo $v->title; ?></a>
                                    <?php }} ?>
                                </div>
                            </div>
                        <?php }else{ ?>
                            <div class="col-12 col-lg-6">
                                <div class="row justify-content-center">
                                    <h1 class="col-12"><?php echo $value->title; ?></h1>
                                    <p class="col-12"><?php echo $value->description; ?></p>
                                    <?php foreach ($listCate as $k => $v) { if($v->parent_id==$value->id){ ?>
                                        <a href="<?php echo getUrlCateProduct($v); ?>" class="col-auto bluebg textheigh buttonls"><?php echo $v->title; ?></a>
                                    <?php }} ?>
                                </div>
                            </div>
                            <img class="col-12 col-lg-6" src="<?php echo base_url('public/media/'.$value->thumbnail); ?>">
                        <?php }}} ?>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- newspaper -->
        <?php if(!empty($news_feed)){ ?>
            <div class="container-fluid pagepart darkbg textmedium">
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
     <!-- JSSCRIPT -->
     <style type="text/css">
        #banner{
           width: 100%;
           background-image: url("<?php echo base_url('public/media/'.$banner[0]->thumbnail); ?>");
           background-size: cover;
           background-position: center;
           margin: 86px 0px 0px 0px;
           padding-bottom: 90px;
       }
   </style>
   <script>
    $('#banner').attr('style','height:'+(screen.height-$('#topbar-bg').height())+'px;');
</script>