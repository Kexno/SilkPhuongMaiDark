<?php if(!empty($data)){ ?>
 <div id="items-area" class="col-12 col-lg textmedium">
  <div class="row">
    <?php foreach ($data as $key => $value) { ?>
      <div class="item col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="item-box buttonds">
          <div class="thumb">
            <img src="<?php echo getImageThumb($value->thumbnail,360,240); ?>">
          </div>
          <a href="<?php echo getUrlProduct($value); ?>" id="item-name" class="d-flex justify-content-center"><?php echo $value->title; ?></a>
          <p class="price d-flex justify-content-center">
            <?php echo formatMoney($value->price); ?><span>&nbsp;đ</span>
          </p>
        </div>
      </div>
    <?php } ?>
  </div>    
</div>
<?php } ?>