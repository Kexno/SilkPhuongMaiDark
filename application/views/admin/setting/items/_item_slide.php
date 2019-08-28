<?php $item = !empty($item) ? (object)$item : null; ?>
<?php if (isset($_GET['id'])) $id = $_GET['id']; ?>
<?php if (isset($_GET['meta_key'])) $meta_key = $_GET['meta_key']; ?>
<fieldset>
    <div class="tab-pane" id="tab_store">
        <ul class="nav nav-tabs">
            <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
                <li<?php echo ($lang_code == 'vi') ? ' class="active"' : ''; ?>>
                    <a href="#tab_<?php echo $lang_code . $id; ?>" data-toggle="tab">
                        <?php echo $lang_name; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
                <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>"
                     id="tab_<?php echo $lang_code . $id; ?>">
                    <fieldset style="width: 100%" class="">
                        <div class="col-md-6">
                            <input type="text"
                                   name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id; ?>][<?php echo $lang_code ?>][title]"
                                   class="form-control"
                                   placeholder="Tiêu đề" value="">
                        </div>
                        <div class="col-md-4">
                            <input type="text"
                                   name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id; ?>][<?php echo $lang_code ?>][link]"
                                   class="form-control"
                                   placeholder="Đường dẫn" value="">
                        </div>
                        <div class="col-md-2">
                            <input
                                id="<?php echo $meta_key; ?>_<?php echo $i; ?>"
                                name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id; ?>][<?php echo $lang_code ?>][img]"
                                placeholder="Choose image..."
                                value="<?php echo isset($item->img) ? $item->img : '' ?>"
                                class="form-control col-md-6" type="hidden"
                                style="width: 50%"/>
                            <img onclick="chooseImage('<?php echo $meta_key; ?>_<?php echo $i; ?>')"
                                 src="<?php echo isset($item->img) ? getImageThumb(isset($item->img)) : 'http://via.placeholder.com/100x50'; ?>"
                                 alt="" height="50">
                        </div>
                    </fieldset>
                </div>
            <?php } ?>
        </div>
    </div>

    <i class="glyphicon glyphicon-trash removeInput" onclick="removeInputImage(this)"></i>
</fieldset>