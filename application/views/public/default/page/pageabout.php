        <!-- PAGE CONTENT -->
        <link href="public/css/common.css" rel="stylesheet" type="text/css">
        <link href="public/css/blog.css" rel="stylesheet" type="text/css">
        <div class="kontainer" id="page-content">
            <div class="row">
                <div id="item-area" class="col-12">
                    <div class="row">
                        <?php if(!empty($newsfeed)) foreach ($newsfeed as $key => $value) { ?>
                            <div class="item col-12 col-sm-6 col-md-4">
                                <div class="item-box buttonds textmedium darkbgforcus">
                                    <div class="thumb">
                                        <a href="<?php echo getUrlNews($value); ?>"><img src="<?php echo getImageThumb($value->thumbnail,360,240); ?>"></a>
                                    </div>
                                    <a href="<?php echo getUrlNews($value); ?>" class="item-name textmedium"><?php echo $value->title; ?></a>
                                    <p class="item-descript"><?php echo $value->description; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>            
        </div>
        <script type="text/javascript">
            $('body').addClass('darkbg textmedium');
        </script>
        <style type="text/css">
            .thumb a {
                display: initial;
                height: 300px;
            }
            .thumb a img{
                height: 100%;
            }
        </style>