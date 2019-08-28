<html lang="en">
<head>

    <!-- Required meta tags -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Silk Phương Mai | Sản phẩm</title>

    <!-- CSS-->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="<?php echo base_url(); ?>public/css/common.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>public/css/product-item.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>public/css/fancybox.css" rel="stylesheet" type="text/css">

    <!-- jQuery, Popper.js, Bootstrap JS -->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="<?php echo base_url(); ?>public/css/fancybox.js" ></script>
    <link href="<?php echo base_url(); ?>public/css/toastr.min.css" rel="stylesheet" type="text/css">

    <!-- fonts -->

    <link href="<?php echo base_url(); ?>public/fonts/fontawesome5.9.0/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap&subset=vietnamese" rel="stylesheet">


</head>
<body class="darkbg textmedium">  

    <div class="kontainer" id="page-content">

      <!-- content -->
      <?php $album=json_decode($oneItem->album,true); ?>
      <div class="row">
        <div class="col-12 col-md-6">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel data-interval="false"">
                <ol class="carousel-indicators">
                    <li style="background-image: url('<?php echo base_url('public/media/'.$oneItem->thumbnail); ?>')" data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <?php if(!empty($album)) foreach ($album as $key => $value) { ?>
                        <li style="background-image: url('<?php echo base_url('public/media/'.$value); ?>')" data-target="#carouselExampleIndicators" data-slide-to="<?php echo $key+1; ?>"></li>
                    <?php } ?>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="item">
                    <a href="<?php echo base_url('public/media/'.$oneItem->thumbnail); ?>" class="carousel-item active" data-fancybox="gallery">
                        <img src="<?php echo base_url('public/media/'.$oneItem->thumbnail); ?>">
                    </a>
                </div>
                    <?php if(!empty($album)) foreach ($album as $key => $value) { ?>
                        <div class="item">
                        <a href="<?php echo base_url('public/media/'.$value); ?>" class="carousel-item" data-fancybox="gallery">
                        <img src="<?php echo base_url('public/media/'.$value); ?>">
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="row justify-content-center">
            <h2 class="col-12"><?php echo $oneItem->title; ?></h2>
            <h4 class="col-12 price"><?php echo formatMoney($oneItem->price); ?><span>&nbsp;đ</span></h4>
            <p class="col-12 info">
                <?php echo $oneItem->content; ?>
            </p>
            <button class="col-auto buy bluebg buttonds" type="button" data-toggle="modal" data-target="#buyform">
                <h6 class="textheigh">MUA NGAY</h6>
                <div class="textheigh">Gọi điện xác nhận và giao hàng tận nơi</div>
            </button>
        </div>
    </div>
</div>
<div class="row justify-content-center" style="padding:10px;">
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v4.0&appId=599235853852407&autoLogAppEvents=1"></script>
    <div class="fb-comments" style="background: rgba(255,255,255,0.8);" data-href="<?php echo getUrlProduct($oneItem); ?>" data-width="100%;" data-numposts="5"></div>

</div>

</div>
<!-- BUY PRODUCT FORM -->

<div class="modal fade textheigh" id="buyform" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bluebg">
            <div class="modal-body">
                <div class="row">
                    <h6 clas type="button" id="close-form" class="col-auto" data-dismiss="modal" aria-label="Close">
                        <span class="textheigh" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <img class="col-12 col-sm-4 align-self-center" src="<?php echo getImageThumb($oneItem->thumbnail); ?>">
                    <form class="col-12 col-sm-8" id="form_order">
                        <h6>Thông tin người mua</h6>
                        <input type="hidden" name="ward_id" value="<?php echo $oneItem->id; ?>">
                        <input type="radio" name="code" id="male" value="male">
                        <label for="male"><span></span>Nam</label>
                        <input type="radio" name="code" id="female" value="female">
                        <label for="female"><span></span>Nữ</label>
                        <input type="radio" name="code" id="other" value="other">
                        <label for="other"><span></span>Khác</label>
                        <input class="col-12" type="text" name="full_name" placeholder="Họ & tên . . .">
                        <input class="col-12" type="text" name="phone" placeholder="Số điện thoại . . .">
                        <textarea class="col-12" type="text" name="address" placeholder="Địa chỉ nhận hàng . . ."></textarea>
                        <textarea class="col-12" type="text" name="note" placeholder="Ghi chú đơn hàng . . ."></textarea>
                        <div>Tổng: <?php echo formatMoney($oneItem->price); ?>&nbsp;đ</div>
                        <button type="submit" class="buttonls">ĐẶT HÀNG NGAY</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JSSCRIPT -->
<script>
    $(document).ready(function(){
        $('.dropdown-submenu > a').on("click", function(e) {
            var submenu = $(this);
            $('.dropdown-submenu .dropdown-menu').removeClass('show');
            submenu.next('.dropdown-menu').addClass('show');
            e.stopPropagation();
        });

        $('.dropdown').on("hidden.bs.dropdown", function() {
                    // hide any open menus when parent closes
                    $('.dropdown-menu.show').removeClass('show');
                });
    });
</script>

</body>
</html>