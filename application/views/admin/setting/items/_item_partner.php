<?php if (isset($data['id'])) $id = $data['id']; ?>
<?php if (isset($data['meta_key'])) $meta_key = $data['meta_key'];
?>
<fieldset>
  <div class="col-md-12">
    <div class="tab-content">
      <div class="tab-pane active">
        <div class="row _flex" style="">
          <div class="col-md-5">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Tiêu đề"
                     name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id ?>][name]" id=""
                     value="">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <?php
                if(!empty($list_cat)){
                  ?>
                  <select name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id ?>][link]" class="select2 form-control">
                    <?php foreach ($list_cat as $item): ?>
                    <option value="<?php echo $item->id; ?>"><?php echo $item->title; ?></option>
                  <?php endforeach; ?>
                  </select>
                  <?php
                }else{
                  ?>
                  <input type="text" class="form-control" placeholder="Link"
                         name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id ?>][link]" id=""
                         value="">
                  <?php
                }
              ?>

            </div>
          </div>
          <div class="col-md-3">
            <input
              id="<?php echo $meta_key; ?>_<?php echo $meta_key . $id ?>_img"
              name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id ?>][img]"
              class="form-control col-md-6" type="hidden"  style="width: 50%"/>
            <img onclick="chooseImage('<?php echo $meta_key; ?>_<?php echo $meta_key . $id ?>_img')"
                 src="http://via.placeholder.com/100x50" alt="" width="100">
          </div>
        </div>
      </div>
    </div>
  </div>
  <i class="glyphicon glyphicon-trash removeInput" onclick="removeInputImage(this)"></i>

</fieldset>