<?php

defined('BASEPATH') OR exit('No direct script access allowed');
$controller = $this->router->fetch_class();
$method = $this->router->fetch_method();
?>
<div class="col-sm-7 col-xs-12">
  <?php
  if (in_array($controller, ['category', 'post', 'product', 'banner', 'tour', 'voucher', 'project', 'report', 'course', 'suggest_product'])):

    ?>
	<!-- <div class="col-md-4"> -->
		<div class="form-group">
		  <div class="input-group">
			<span class="input-group-addon"><i class="fa fa-filter"></i></span>
			<select class="form-control select2 filter_category" title="filter_category_id" name="category_id"
					style="width: 100%;" tabindex="-1" aria-hidden="true">
			  <option value="0"><?php echo lang('from_category'); ?></option>
			</select>
		  </div>
		</div>
	<!-- </div> -->
  <?php endif; ?>
</div>
