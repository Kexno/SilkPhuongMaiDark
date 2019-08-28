<div class="tab-pane active" id="tab_general">
  <div class="box-body">
    <div class="form-group">
      <label><?php echo lang('form_favicon'); ?></label>
      <div class="input-group input-group-lg">
        <span class="input-group-addon" onclick="chooseImage('favicon')"
        data-toggle="tooltip" title="<?php echo lang('btn_select_image'); ?>"><i
        class="fa fa-fw fa-image"></i></span>
        <input id="favicon" name="favicon"
        value="<?php echo isset($favicon) ? $favicon : ''; ?>"
        placeholder="<?php echo lang('form_favicon'); ?>" class="form-control"
        type="text"/>
        <span class="input-group-addon"><a class="fancybox"
         href="<?php echo getImageThumb(isset($favicon) ? $favicon : '') ?>"
         title="Click để xem ảnh"> <img
         src="<?php echo getImageThumb(isset($favicon) ? $favicon : '') ?>"
         width="30"></a> </span>
       </div>
     </div>
     <div class="form-group">
      <label>Logo Home</label>
      <div class="input-group input-group-lg">
        <span class="input-group-addon" onclick="chooseImage('logo')"
        data-toggle="tooltip" title="<?php echo lang('btn_select_image'); ?>"><i
        class="fa fa-fw fa-image"></i></span>
        <input id="logo" name="logo" value="<?php echo isset($logo) ? $logo : ''; ?>"
        placeholder="Logo Home" class="form-control"
        type="text"/>
        <span class="input-group-addon"><a class="fancybox"
         href="<?php echo getImageThumb(isset($logo) ? $logo : '') ?>"
         title="Click để xem ảnh"> <img
         src="<?php echo getImageThumb(isset($logo) ? $logo : '', 64, 45) ?>"
         width="30"> </a></span>
       </div>
     </div>
   </div>
   <ul class="nav nav-tabs">
    <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
      <li<?php echo ($lang_code == 'vi') ? ' class="active"' : ''; ?>><a
      href="#tab_<?php echo $lang_code; ?>"
      data-toggle="tab"><img
      src="<?php echo $this->templates_assets; ?>/flag/<?php echo $lang_code ?>.png"> <?php echo $lang_name; ?>
    </a></li>
  <?php } ?>
</ul>

<div class="tab-content">
  <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
    <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>"
     id="tab_<?php echo $lang_code; ?>">
     <div class="box-body">
      <div class="form-group">
        <label><?php echo lang('form_name'); ?></label>
        <input name="meta[<?php echo $lang_code; ?>][name]"
        placeholder="<?php echo lang('form_name'); ?>"
        class="form-control" type="text"
        value="<?php echo isset($meta[$lang_code]['name']) ? $meta[$lang_code]['name'] : ''; ?>"/>
      </div>

      <div class="form-group">
        <label>Mô tả</label>
        <input name="meta[<?php echo $lang_code; ?>][description]"
        placeholder="Mô tả"
        class="form-control" type="text"
        value="<?php echo isset($meta[$lang_code]['description']) ? $meta[$lang_code]['description'] : ''; ?>"/>
      </div>

      <div class="form-group">
        <label>Tiêu đề SEO</label>
        <input name="meta[<?php echo $lang_code; ?>][title]"
        placeholder="Tiêu đề SEO"
        class="form-control" type="text"
        value="<?php echo isset($meta[$lang_code]['title']) ? $meta[$lang_code]['title'] : ''; ?>"/>
      </div>

      <div class="form-group">
        <label>Mô tả SEO Website</label>
        <textarea name="meta[<?php echo $lang_code; ?>][meta_desc]"
          class="form-control"><?php echo isset($meta[$lang_code]['meta_desc']) ? $meta[$lang_code]['meta_desc'] : ''; ?></textarea>
        </div>

        <div class="form-group">
          <label>Từ khóa SEO Website</label>
          <input name="meta[<?php echo $lang_code; ?>][meta_keyword]"
          placeholder="Từ khóa SEO Website"
          class="form-control" type="text"
          value="<?php echo isset($meta[$lang_code]['meta_keyword']) ? $meta[$lang_code]['meta_keyword'] : ''; ?>"/>
        </div>
      </div>
    </div>
  <?php } ?>

</div>

</div>
