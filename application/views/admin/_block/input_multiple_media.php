<?php

defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="form-group">
    <fieldset class="form-group album-contain">
        <legend>Album ảnh</legend>
        <div data-id="0" id="gallery"><!--<input type="hidden" name="album[]">--></div>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <button type="button" class="btn btn-primary btnAddMore"
                    onclick="chooseMultipleMedia('gallery')">
                <i class="fa fa-plus"> <?php echo lang('btn_add'); ?> </i>
            </button>
        </div>
    </fieldset>
    <div class="error-multiple-media"></div>
</div>
