<div class="tab-pane" id="tab_social">
    <div class="box-body">
        <div class="form-group">
            <label><?php echo lang('form_social_fb'); ?></label>
            <input name="social_fb" placeholder="<?php echo lang('form_social_fb'); ?>"
                   class="form-control" type="text"
                   value="<?php echo isset($social_fb) ? $social_fb : ''; ?>"/>
        </div>
        <div class="form-group">
            <label><?php echo lang('form_social_google'); ?></label>
            <input name="social_google"
                   placeholder="<?php echo lang('form_social_google'); ?>"
                   class="form-control" type="text"
                   value="<?php echo isset($social_google) ? $social_google : ''; ?>"/>
        </div>
        <div class="form-group">
            <label>Twitter</label>
            <input name="social_twitter"
                   placeholder="Twitter"
                   class="form-control" type="text"
                   value="<?php echo isset($social_twitter) ? $social_twitter : ''; ?>"/>
        </div>
        <div class="form-group">
            <label><?php echo lang('form_social_youtube'); ?></label>
            <input name="social_youtube"
                   placeholder="<?php echo lang('form_social_youtube'); ?>"
                   class="form-control" type="text"
                   value="<?php echo isset($social_youtube) ? $social_youtube : ''; ?>"/>
        </div>
    </div>
</div>