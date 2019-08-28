<?php

defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title">Meta SEO</h3>
    </div>
    <div class="box-body">
        <div class="form-group">
            <label>Meta Title</label>
            <label for="title"><span class="count-title">0</span> / <?php echo $this->config->item('SEO_title_maxlength') ?></label>
            <input value="" id="meta_title_<?php echo $lang_code ?>" name="meta_title[<?php echo $lang_code ?>]" placeholder="Meta Title" class="form-control" type="text" maxlength="55"/>
        </div>
        <div class="form-group">
            <label>Url</label>
            <input id="slug_<?php echo $lang_code ?>" name="slug[<?php echo $lang_code ?>]" placeholder="Url" class="form-control" type="text" />
        </div>
        <div class="form-group">
            <label>Meta Description</label>
            <label for="desc"><span class="count-desc">0</span> / <?php echo $this->config->item('SEO_description_maxlength') ?></label>
            <textarea id="meta_description_<?php echo $lang_code ?>" name="meta_description[<?php echo $lang_code ?>]" placeholder="Meta Description" class="form-control content_product"></textarea>
        </div>
        <div class="form-group">
            <label>Meta Keyword</label>
            <label for="key"><span class="count-key">0</span> / <?php echo $this->config->item('SEO_keyword_maxlength') ?></label>
            <input value="" id="meta_keyword_<?php echo $lang_code ?>" name="meta_keyword[<?php echo $lang_code ?>]" placeholder="Meta Keyword" class="form-control tagsinput" data-role="tagsinput" type="text" />
        </div>
        <div class="google">
            <h2 class="cgg"><span class="gg_1"> Google!</span></h2>
            <input type="text" class="gg-result" readOnly/>
            <div class="box">
                <h3 class="gg-title"></h3>
                <cite class="gg-url"></cite>
                <span class="gg-desc"></span>
            </div>
        </div>
    </div>
</div>