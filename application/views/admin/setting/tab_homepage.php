<div class="tab-pane" id="tab_homepage">
    <div class="form-group" style="margin-left: 15px;">
        <input type="checkbox" <?php echo isset($check_banner_video) ? 'checked' : '' ?> name="check_banner_video" value="1"/>
        <label>Chọn banner là video</label>
    </div>
    <div class="form-group" style="margin-top: 10px">
        <label for="">Url video</label>
        <input type="text" <?php echo !isset($check_banner_video) ? 'readonly' : '' ?> class="form-control" name="url_video_home"
        value="<?php echo !empty($url_video_home) ? $url_video_home : '' ?>">
    </div>

    <ul class="nav nav-tabs">
        <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
            <li<?php echo ($lang_code == 'vi') ? ' class="active"' : ''; ?>><a
            href="#tab1_<?php echo $lang_code; ?>"
            data-toggle="tab"><img
            src="<?php echo $this->templates_assets; ?>/flag/<?php echo $lang_code ?>.png"> <?php echo $lang_name; ?>
        </a>
    </li>
<?php } ?>
</ul>
<div class="tab-content">
    <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
        <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>"
         id="tab1_<?php echo $lang_code; ?>">
         <div class="box-body">
            <div class="row">
                <div class="form-group">
                    <label>Tiêu đề footer</label>
                    <input name="home[<?php echo $lang_code; ?>][description_footer]"
                    placeholder="Tiêu đề footer"
                    class="form-control" type="text"
                    value="<?php echo isset($home[$lang_code]['description_footer']) ? $home[$lang_code]['description_footer'] : ''; ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label>Slogan</label>
                    <input name="home[<?php echo $lang_code; ?>][slogan]"
                    placeholder="Slogan"
                    class="form-control" type="text"
                    value="<?php echo isset($home[$lang_code]['slogan']) ? $home[$lang_code]['slogan'] : ''; ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label>Mô tả nhóm sản phẩm</label>
                    <input name="home[<?php echo $lang_code; ?>][description_category]"
                    placeholder="Mô tả nhóm sản phẩm"
                    class="form-control" type="text"
                    value="<?php echo isset($home[$lang_code]['description_category']) ? $home[$lang_code]['description_category'] : ''; ?>"/>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="form-group">
                    <label>Mô tả chứng chỉ & chứng nhận</label>
                    <input name="home[<?php echo $lang_code; ?>][description_certificate]"
                    placeholder="Mô tả chứng chỉ & chứng nhận"
                    class="form-control" type="text"
                    value="<?php echo isset($home[$lang_code]['description_certificate']) ? $home[$lang_code]['description_certificate'] : ''; ?>"/>
                </div>
            </div>
            <hr>
        </div>
    </div>
<?php } ?>
</div>

</div>