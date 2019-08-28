<style type="text/css">
    div.details-news div{
        margin: 10px;
    }
    div.tab-content img{
        margin: 5px;
        border-radius: 10px;
    }
    h4.title{
        font-size: 20px;
        overflow: hidden;
        -o-text-overflow: ellipsis;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        line-height: 24px;
        max-height: calc(24px * 2);
    }
</style>
<section class="page-news-dt page-primary v2">
    <div class="container">
        <div class="wrap-news-dt">
            <div class="row">
                <div class="col-lg-8">
                    <?php if (!empty($oneItem)): ?>
                        <div class="details-news">
                            <h1 class="title-news"><?php echo $oneItem->title ?></h1>
                            <div class="control">
                                <span class="time">Ngày đăng: <?php echo formatDate($oneItem->created_time) ?></span>
                                <div class="like-share">
                                    <div class="fb-like" data-href="<?php echo getUrlNews($oneItem) ?>" data-width="" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="true"></div>
                                </div>
                            </div>
                            <div class="desc">
                                <?php echo $oneItem->description ?>
                            </div>
                            <div class="s-content">
                                <?php echo $oneItem->content ?>
                            </div>
                            <div class="control v2">
                                <div class="like-share">
                                    <div class="fb-like" data-href="<?php echo getUrlNews($oneItem) ?>" data-width="" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="true"></div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-4">
                    <div class="sb-news" style="">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active show" data-toggle="tab" href="#tabs1"><?php echo lang('related_news') ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs2"><?php echo lang('latest_news') ?></a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="tabs1">
                                <?php if (!empty($list_related)):
                                    foreach ($list_related as $value):
                                        ?>
                                        <div class="item-sb-news row">
                                            <a href="<?php echo getUrlNews($value) ?>" title="" class="img-primary col-6"><img src="<?php echo getImageThumb($value->thumbnail,155,134); ?>" alt="<?php echo $value->title ?>"></a>
                                            <div class="ct col-6">
                                                <h4 class="title"><a href="<?php echo getUrlNews($value) ?>" title=""><?php echo $value->title ?></a></h4>
                                                <span class=""><?php echo formatDate($value->created_time) ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach;
                                endif;
                                ?>
                            </div>
                            <div class="tab-pane fade" id="tabs2">
                                <?php if (!empty($list_news)):
                                    foreach ($list_news as $value):
                                        ?>
                                        <div class="item-sb-news row">
                                            <a href="<?php echo getUrlNews($value) ?>" title="" class="img-primary col-6"><img src="<?php echo getImageThumb($value->thumbnail,155,134); ?>" alt="<?php echo $value->title ?>"></a>
                                            <div class="ct col-6">
                                                <h4 class="title"><a href="<?php echo getUrlNews($value) ?>" title=""><?php echo $value->title ?></a></h4>
                                                <span class=""><?php echo formatDate($value->created_time) ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach;
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $('body').addClass('darkbg textmedium');
    $('.page-news-dt').attr('style','margin-top:'+($('#topbar-bg').height()+15)+'px;');
</script>