
<?php
$item = !empty($item) ? (object)$item : null;
?>
<?php if (isset($_POST['id'])) $id = $_POST['id']; ?>
<?php if (isset($_POST['meta_key'])) $meta_key = $_POST['meta_key']; ?>

<fieldset>
    <div class="col-md-12">
        <select name="<?php echo $meta_key; ?>[<?php echo $meta_key . $i; ?>][id]" class="form-control select2">
            <option value="">-- Chọn trang --</option>
            <?php
            if(!empty($list_data)) foreach ($list_data as $data){
                echo '<option value="'.$data->id.'">'.$data->title.'</option>';
            }
            ?>
        </select>
    </div>
    <i class="glyphicon glyphicon-trash removeInput" onclick="removeInputImage(this)"></i>
</fieldset>
