        <!-- PAGE CONTENT -->

        <div class="kontainer" id="page-content">

            <div class="row">
                <h1 class="col-12 d-flex justify-content-center">Lụa tơ tằm Phương Mai</h1>
                <div class="col-12 col-md-6">
                  <?php echo $oneItem->content; ?>
                </div>
                <div class="col-12 col-md-6">
                    <?php echo $oneItem->content_more; ?>
                </div>
                <img class="col-12 img-fw" src="<?php echo base_url('public/media/'.$oneItem->thumbnail); ?>">
                <i class="col-12 img-descript d-flex justify-content-center">Hình ảnh . . .<?php echo $oneItem->thumbnail; ?></i>
            </div>
            
        </div>
        <script type="text/javascript">
            $('body').addClass('darkbg textmedium');
        </script>