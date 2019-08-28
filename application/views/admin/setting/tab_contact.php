<div class="tab-pane" id="tab_contact">

    <ul class="nav nav-tabs">
        <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
            <li<?php echo ($lang_code == 'vi') ? ' class="active"' : ''; ?>>
                <a href="#tab_contact1_<?php echo $lang_code; ?>" data-toggle="tab">
                    <img src="<?php echo $this->templates_assets; ?>/flag/<?php echo $lang_code ?>.png"> <?php echo $lang_name; ?>
                </a>
            </li>
        <?php } ?>
    </ul>

    <div class="tab-content">
        <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
            <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>"
                 id="tab_contact1_<?php echo $lang_code; ?>">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tên công ty</label>
                                <input name="contact[<?php echo $lang_code; ?>][company]"
                                       placeholder="Tên công ty"
                                       class="form-control" type="text"
                                       value="<?php echo !empty($contact[$lang_code]['company']) ? $contact[$lang_code]['company'] : ''; ?>"/>
                            </div>
                            <div class="form-group">
                                <label>Địa chỉ</label>
                                <input name="contact[<?php echo $lang_code; ?>][address]"
                                       placeholder="Địa chỉ"
                                       class="form-control" type="text"
                                       value="<?php echo !empty($contact[$lang_code]['address']) ? $contact[$lang_code]['address'] : ''; ?>"/>
                            </div>
                               <div class="form-group">
                                <label>Văn phòng đại diện</label>
                                <input name="contact[<?php echo $lang_code; ?>][office]"
                                       placeholder="Văn phòng đại diện"
                                       class="form-control" type="text"
                                       value="<?php echo !empty($contact[$lang_code]['office']) ? $contact[$lang_code]['office'] : ''; ?>"/>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input name="contact[<?php echo $lang_code; ?>][email]"
                                       placeholder="Email"
                                       class="form-control" type="text"
                                       value="<?php echo !empty($contact[$lang_code]['email']) ? $contact[$lang_code]['email'] : ''; ?>"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Điện thoại</label>
                                <input name="contact[<?php echo $lang_code; ?>][phone]"
                                       placeholder="Điện thoại"
                                       class="form-control" type="text"
                                       value="<?php echo !empty($contact[$lang_code]['phone']) ? $contact[$lang_code]['phone'] : ''; ?>"/>
                            </div>
                            <div class="form-group">
                                <label>Hotline</label>
                                <input name="contact[<?php echo $lang_code; ?>][hotline]"
                                       placeholder="Hotline"
                                       class="form-control" type="text"
                                       value="<?php echo !empty($contact[$lang_code]['hotline']) ? $contact[$lang_code]['hotline'] : ''; ?>"/>
                            </div>
                            <div class="form-group">
                                <label>Time</label>
                                <input name="contact[<?php echo $lang_code; ?>][time]"
                                       placeholder="Time"
                                       class="form-control" type="text"
                                       value="<?php echo !empty($contact[$lang_code]['time']) ? $contact[$lang_code]['time'] : ''; ?>"/>
                            </div>


                        </div>
                    </div>

                </div>
            </div>
        <?php } ?>

    </div>
</div>
