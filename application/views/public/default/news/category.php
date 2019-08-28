        <link href="<?php echo base_url(); ?>public/css/common.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>public/css/blog.css" rel="stylesheet" type="text/css">
        <!-- PAGE CONTENT -->

        <div class="kontainer" id="page-content">
            <div class="row">
                <div class="col-12">
                    <div id="banner" class="d-flex justify-content-center align-items-center"><?php echo $oneItem->title; ?></div>
                </div>
                <div id="item-area" class="row" style="padding: 15px;">
                    <?php if(!empty($data)) foreach ($data as $key => $value) { ?>
                        <div class="item col-12 col-sm-6 col-md-3">
                            <div class="item-box buttonds textmedium darkbgforcus">
                                <div class="thumb">
                                    <a href="<?php echo getUrlNews($value); ?>"><img src="<?php echo getImageThumb($value->thumbnail,360,240); ?>"></a>
                                </div>
                                <a href="<?php echo getUrlNews($value); ?>" class="item-name textmedium"><?php echo $value->title; ?></a>
                                <p class="item-descript"><?php echo $oneItem->description; ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php echo !empty($pagination)?$pagination:''; ?>
            </div>            
        </div>
        <script type="text/javascript">
            $('body').addClass('darkbg textmedium');
        </script>
        <style type="text/css">
            #banner{
               width: 100%;
               background-image: url("<?php echo base_url('public/media/'.$oneItem->thumbnail); ?>");
               background-size: cover;
               background-position: center;
               margin: 86px 0px 0px 0px;
               padding-bottom: 90px;
           }
       </style>
       <script>
        $('#banner').attr('style','height:'+(screen.height/2)+'px;');
    </script>
    <style type="text/css">
        .thumb a {
            display: initial;
            height: 300px;
        }
        .thumb a img{
            height: 100%;
        }
        #page-content a, #msidebar a {
            color: #2aa6d3;
        }
        .blognav li.active a{
            background-color: #2aa6d3;
            color: rgba(247, 251, 253, 0.9) !important;
        }
    </style>