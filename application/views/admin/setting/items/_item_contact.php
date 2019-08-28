<?php $item = !empty($item) ? (object)$item : null; ?>
<?php if (isset($_GET['id'])) $id = $_GET['id']; ?>
<?php if (isset($_GET['meta_key'])) $meta_key = $_GET['meta_key']; ?>
<fieldset>
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label>Tên công ty</label>
                <input name="<?php echo $meta_key ?>[<?php echo $id; ?>][name_company]" class="form-control"
                       placeholder="Tên công ty"
                       value="<?php echo !empty($contact_featured[$id]['name_company']) ? $contact_featured[$id]['name_company'] : ''; ?>">
            </div>
            <div class="form-group">
                <label>Hotline</label>
                <input name="<?php echo $meta_key ?>[<?php echo $id; ?>][hotline]" class="form-control"
                       placeholder="Số điện thoại"
                       value="<?php echo !empty($contact_featured[$i]['hotline']) ? $contact_featured[$i]['hotline'] : ''; ?>">
            </div>
            <div class="form-group">
                <label>Fax</label>
                <input name="<?php echo $meta_key ?>[<?php echo $id; ?>][fax]" class="form-control"
                       placeholder="Số fax"
                       value="<?php echo !empty($contact_featured[$i]['fax']) ? $contact_featured[$i]['fax'] : ''; ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input name="<?php echo $meta_key ?>[<?php echo $id; ?>][email]" class="form-control"
                       placeholder="Email"
                       value="<?php echo !empty($contact_featured[$i]['email']) ? $contact_featured[$i]['email'] : ''; ?>">
            </div>
            <div class="form-group">
                <label>Trụ sở</label>
                <input name="<?php echo $meta_key ?>[<?php echo $id; ?>][truso]" class="form-control"
                       placeholder="Trụ sở"
                       value="<?php echo !empty($contact_featured[$i]['truso']) ? $contact_featured[$i]['truso'] : ''; ?>">
            </div>
            <div class="form-group">
                <label>Website</label>
                <input name="<?php echo $meta_key ?>[<?php echo $id; ?>][website]" class="form-control"
                       placeholder="Website"
                       value="<?php echo !empty($contact_featured[$i]['website']) ? $contact_featured[$i]['website'] : ''; ?>">
            </div>
            <div class="form-group">
                <label>Link map</label>
                <input name="<?php echo $meta_key ?>[<?php echo $id; ?>][link_map]" class="form-control"
                       placeholder="Link tới google map"
                       value="<?php echo !empty($contact_featured[$i]['link_map']) ? $contact_featured[$i]['link_map'] : ''; ?>">
            </div>
            <div class="form-group">
                <label>Fanpage</label>
                <textarea name="<?php echo $meta_key ?>[<?php echo $id; ?>][fanpage]" id="" class="form-control"
                          style="height: 70px"><?php echo isset($contact_featured[$i]['fanpage']) ? trim($contact_featured[$i]['fanpage']) : ''; ?></textarea>
            </div>
        </div>
    </div>
</fieldset>