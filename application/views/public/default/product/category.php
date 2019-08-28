<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link href="<?php echo base_url(); ?>public/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>public/css/store.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>public/css/jquery-ui.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo base_url(); ?>public/css/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>public/css/jssor.slider.js"></script>
<?php $listCate=getListCate(); ?>
<div class="kontainer" id="page-content">

  <div class="row">

    <!-- CATEGORY SIDEBAR -->
    <?php if(!empty($listCate)){ ?>
      <div id="sidebar" class="col-lg-auto d-none d-lg-block textmedium">
        <h5>Danh mục sản phẩm</h5>
        <ul class="list">
          <?php foreach ($listCate as $key => $value) {
            if($value->parent_id==0){
             ?>
             <li>
              <div class="category"><?php echo $value->title; ?></div>
              <ul>
                <?php foreach ($listCate as $k => $v) { if($v->parent_id==$value->id){
                  ?>
                  <li><a href="<?php echo getUrlCateProduct($v); ?>"><?php echo $v->title; ?></a></li>
                <?php }} ?>
              </ul>
            </li>
          <?php }} ?>
        </ul>
        <?php if($pricemax!=$pricemin){ ?>
          <div class="rangeprice" style="padding-bottom: 15px;">
            <div class="sb-ct">
              <div class="price-filter" data-id="317">
                <span class="price-1" name="price-1"><span></span> đ</span>
                <input type="hidden" class="price-1">
                <span style="float: right;" class="price-2" name="price-2"><span></span> đ</span>
                <input type="hidden" class="price-2">

                <div id="slider-range" name="filter_price" value="" data-max="<?php echo $pricemax; ?>" data-min="<?php echo $pricemin; ?>" data-value="<?php echo $pricemin; ?>,<?php echo $pricemax; ?>"></div>
                <input type="hidden" id="slider-range" name="filter_price" data-max="<?php echo $pricemax; ?>" data-min="<?php echo $pricemin; ?>" data-value="<?php echo $pricemin; ?>,<?php echo $pricemax; ?>">
              </div>
            </div>
          </div>
        <?php } ?>
        <!-- price filter -->

                   <!--  <div id="ranger">
                        <h5>Giá</h5>
                        <input type="range" class="slider" min="" max="">
                      </div> -->
                      <!-- tags -->
                      <?php if(!empty($oneItem->meta_keyword)){ $meta=explode(',', $oneItem->meta_keyword); ?>
                      <h5>Tag sản phẩm</h5>         
                      <div id="tags">
                        <?php foreach ($meta as $key => $value) {
                         ?>
                         <a href="#" class="bluebg"><?php echo $value; ?></a>
                       <?php } ?>
                     </div>
                   <?php } ?>
                 </div>
               <?php } ?>
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
            </div>
            <div class="row textmedium">
              <h4 class="col-12">Info...</h4>
              <p class="col-12">
                <?php echo $oneItem->description; ?>
              </p>
              <img class="col-12 img-fw" src="<?php echo base_url('public/media/'.$oneItem->thumbnail); ?>">
              <p class="col-12 img-descript ">Hình ảnh . . . <?php echo $oneItem->thumbnail; ?></p>
            </div>

          </div>

          <!-- MOBLIE SIDEBAR -->
          <?php if(!empty($listCate)){ ?>
            <div class="modal fade right" id="sidebar-right" tabindex="-1" role="dialog">
              <div class="modal-dialog modal-sm" role="document">
                <button id="close-sidebar" type="button" class="close" data-dismiss="modal">
                  <img src="<?php echo base_url(); ?>public/images//common/right.svg"> 
                </button>
                <div class="modal-content textmedium">
                  <div class="modal-body" id="msidebar">
                    <h5>Danh mục sản phẩm</h5>
                    <ul class="list">
                      <?php foreach ($listCate as $key => $value) {
                        if($value->parent_id==0){
                         ?>
                         <li>
                          <div class="category"><?php echo $value->title; ?></div>
                          <ul>
                            <?php foreach ($listCate as $k => $v) { if($v->parent_id==$value->id){
                              ?>
                              <li><a href="<?php echo getUrlCateProduct($v); ?>"><?php echo $v->title; ?></a></li>
                            <?php }} ?>
                          </ul>
                        </li>
                      <?php }} ?>
                    </ul>

                    <!-- price filter -->

                           <!--  <div id="ranger">
                                <h5>Giá</h5>
                                <input type="range" class="slider" min="1000" max="500000" value="2000">
                              </div> -->

                              <!-- tags -->
                              
                              <?php if(!empty($oneItem->meta_keyword)){ $meta=explode(',', $oneItem->meta_keyword); ?>
                              <h5>Tag sản phẩm</h5>         
                              <div id="tags">
                                <?php foreach ($meta as $key => $value) {
                                 ?>
                                 <a href="#" class="bluebg"><?php echo $value; ?></a>
                               <?php } ?>
                             </div>
                           <?php } ?>
                         </div>
                       </div>
                     </div>
                   </div>
                   
                 <?php } ?>
                 <button id="open-sidebar" type="button" data-toggle="modal" data-target="#sidebar-right" class="btn btn-primary navbar-btn pull-left d-lg-none">
    <img src="<?php echo base_url(); ?>public/images/common/left.svg">    
</button>
                 <script type="text/javascript">
                  $('body').addClass('darkbg');
                  var get_currency = function(x){
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+'đ';
                  }
                  $("#slider-range").each(function() {
                    var this_ = $(this);

                    var min = parseInt(this_.attr('data-min'));

                    var max = parseInt(this_.attr('data-max'));
                    var valu = this_.attr('data-value').split(",");
                    var rang = max- min;

                    this_.slider({
                      range: true,
                      min: min,
                      max: max,
                      values: valu,
                      slide: function( event, ui ) {
                        var left1 = (ui.values[0] - min)/rang*100;
                        var price1 = this_.prevAll('.price-1').text(get_currency(ui.values[0]));
                        var left2 = 100 - (ui.values[1] - min)/rang*100;
                        var price2 = this_.prevAll('.price-2').text(get_currency(ui.values[1]));
                        price1.trigger('change');
                        price2.trigger('change');
                        loadpr(ui.values[0],ui.values[1]);
                      }
                    });

                    var left1 = (this_.slider( "values", 0 ) - min)/rang*100;
                    this_.prevAll('.price-1').text(get_currency(this_.slider( "values", 0 )));
                    var left2 = 100 - (this_.slider( "values", 1 ) - min)/rang*100;
                    this_.prevAll('.price-2').text(get_currency(this_.slider( "values", 1 )));
                  });
                  function loadpr(min,max){
                    $.ajax({
                      url : base_url+"/product/getpr/"+min+'/'+max,
                      type: "GET",
                      dataType: "JSON",
                      success: function(data)
                      {
                        $('#items-area').html(data);
                      }
                    });
                  }
                </script>