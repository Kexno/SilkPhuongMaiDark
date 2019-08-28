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
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text"
                                       name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id; ?>][<?php echo $lang_code ?>][location]"
                                       class="form-control"
                                       placeholder="Chi nhánh" value="">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text"
                                       name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id; ?>][<?php echo $lang_code ?>][address]"
                                       class="form-control"
                                       placeholder="Địa chỉ" value="">
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text"
                                       name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id; ?>][<?php echo $lang_code ?>][tel]"
                                       class="form-control"
                                       placeholder="Số điện thoại" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text"
                                       name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id; ?>][<?php echo $lang_code ?>][fax]"
                                       class="form-control"
                                       placeholder="Fax" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text"
                                       name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id; ?>][<?php echo $lang_code ?>][email]"
                                       class="form-control"
                                       placeholder="Email" value="">
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input
                                    name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id; ?>][<?php echo $lang_code ?>][long]"
                                    placeholder="Vĩ độ (longitude)"
                                    class="form-control" type="text"
                                    value=""/>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input
                                    name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id; ?>][<?php echo $lang_code ?>][lat]"
                                    placeholder="Vĩ độ (longitude)"
                                    class="form-control" type="text"
                                    value=""/>
                            </div>
                        </div>
                    </fieldset>
                </div>
            <?php } ?>
        </div>
    </div>

    <i class="glyphicon glyphicon-trash removeInput" onclick="removeInputImage(this)"></i>
</fieldset>