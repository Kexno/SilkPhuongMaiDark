<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_general" data-toggle="tab">Chung</a>
        </li>

        <li>
            <a href="#tab_page" data-toggle="tab">Page</a>
        </li>
        <?php if(!empty($list_category_type)) foreach ($list_category_type as $k => $catg):?>
            <li>
                <a href="#tab_<?php echo $k ?>" data-toggle="tab">Category <?php echo $catg['type']; ?></a>
            </li>
        <?php endforeach; ?>
        <li>
            <a href="#tab_post" data-toggle="tab">Post</a>
        </li>
        <li>
            <a href="#tab_product" data-toggle="tab">Product</a>
        </li>
    </ul>
    <div id="listDataItem" class="tab-content">
        <div class="tab-pane active" id="tab_general">
            <input type="hidden" value="other" name="type">
            <select class="form-control select2"   style="width: 100%;" tabindex="-1" aria-hidden="true">
                <option value="#">Link khác</option>
                <option value="/">Trang chủ</option>
            </select>
        </div>

        <div class="tab-pane" id="tab_page">
            <input type="hidden" value="page" name="type"   style="width: 100%;" tabindex="-1" aria-hidden="true">
            <select class="form-control select2"   style="width: 100%;" tabindex="-1" aria-hidden="true">
                <?php
                if(!empty($list_pages)) foreach ($list_pages as $p):
                    $linkPage = str_replace(base_url(),'',getUrlPage($p));
                    ?>
                    <option value="<?php echo $linkPage; ?>">
                        <?php echo $p->title; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- /.tab-pane -->
        <?php if(!empty($list_category_type)) foreach ($list_category_type as $k => $categ):?>
            <div class="tab-pane" id="tab_<?php echo $k ?>">
                <input type="hidden" value="<?php echo $categ['type'] ?>" name="type">
                <select class="form-control select2"  style="width: 100%;" tabindex="-1" aria-hidden="true">
                    <?php
                    if(!empty($list[$categ['type']])) foreach ($list[$categ['type']] as $cat):
                        switch ($categ['type']){
                            case 'video':
                                $linkPage = str_replace(base_url(),'',getUrlCateVideo($cat));
                                break;
                                case 'product':
                                $linkPage = str_replace(base_url(),'',getUrlCateProduct($cat));
                                break;
                            default:
                                $linkPage = str_replace(base_url(),'',getUrlCateNews($cat));
                        }
                        ?>
                        <option value="<?php echo $linkPage; ?>"><?php echo $cat->title; ?></option>
                    <?php endforeach; ?>
                </select>
                <br/>
            </div>
        <?php endforeach; ?>

        <div class="tab-pane" id="tab_post">
            <input type="hidden" value="page" name="type"   style="width: 100%;" tabindex="-1" aria-hidden="true">
            <select class="form-control select2"   style="width: 100%;" tabindex="-1" aria-hidden="true">
                <?php
                if(!empty($list_posts)) foreach ($list_posts as $p):
                    $linkPage = str_replace(base_url(),'',getUrlNews($p));
                    ?>
                    <option value="<?php echo $linkPage; ?>">
                        <?php echo $p->title; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="tab-pane" id="tab_product">
            <input type="hidden" value="page" name="type"   style="width: 100%;" tabindex="-1" aria-hidden="true">
            <select class="form-control select2"   style="width: 100%;" tabindex="-1" aria-hidden="true">
                <?php
                if(!empty($list_product)) foreach ($list_product as $p):
                    $linkPage = str_replace(base_url(),'',getUrlProduct($p));
                    ?>
                    <option value="<?php echo $linkPage; ?>">
                        <?php echo $p->title; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
    <button type="button" class="btn btn-success addtonavmenu"><i class="glyphicon glyphicon-plus"></i> Thêm vào menu</button>
</div>
<!-- nav-tabs-custom -->
