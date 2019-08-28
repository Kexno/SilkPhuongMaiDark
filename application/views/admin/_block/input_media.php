<?php
/**
 * Created by PhpStorm.
 * User: ducto
 * Date: 18/12/2017
 * Time: 1:23 CH
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$id_image = !empty($id_image) ? $id_image : 'form_thumbnail';
$name_image = !empty($name_image) ? $name_image : 'thumbnail';
$value_image = '';
$title_image=!empty($title_image)?$title_image:'Ảnh đại diện';
?>
<div class="form-group">
	<label><?php echo $title_image; ?></label>
	<div class="input-group input-group-lg">
		<span class="input-group-addon" onclick="chooseImage('<?php echo $id_image ?>')"><i class="fa fa-fw fa-image"></i><?php echo lang('btn_select_image');?></span>
		<input id="<?php echo $id_image ?>" onclick="chooseImage('<?php echo $id_image ?>')" name="<?php echo $name_image ?>" placeholder="<?php echo $title_image; ?>" class="form-control" type="text" value="<?php echo $value_image; ?>"/>
		<span class="input-group-addon" style="padding: 0;"><a href="<?php echo getImageThumb($value_image); ?>" class="fancybox"><img src="<?php echo getImageThumb($value_image); ?>" width="44" height="44"></a></span>
	</div>
</div>
