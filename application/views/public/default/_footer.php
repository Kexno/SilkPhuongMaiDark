            <!-- FOOTER -->
            <footer class="darkbg textmedium">
                <div class="kontainer ">
                    <ul class="d-flex justify-content-center row">
                        <li class="col-auto"><a class="textmedium" href="<?php echo base_url(); ?>">Trang chủ</a></li>
                        <li class="col-auto"><a class="textmedium" href="<?php echo base_url('tong-quan.html'); ?>">Giới thiệu</a></li>
                        <li class="col-auto"><a class="textmedium" href="<?php echo base_url('tong-quan-san-pham.html'); ?>">Sản phẩm</a></li>
                        <li class="col-auto"><a class="textmedium" href="<?php echo base_url('tin-tuc-c2'); ?>">Tin tức</a></li>
                        <li class="col-auto"><a class="textmedium" href="<?php echo base_url('lien-he.html'); ?>">Liên hệ</a></li>
                    </ul>
                    <div class="row">
                        <div class="col-12 col-md-7">
                            <a href="<?php echo base_url(); ?>" class="col-12"><img id="botlogo" src="<?php echo base_url(); ?>public/images/common/logo-bv.svg"></a>
                            <p><b>Địa chỉ:&nbsp;</b><?php echo $this->settings['contact'][$this->session->public_lang_code]['address']; ?></p>
                            <p><b>Văn phòng đại diện:&nbsp;</b><?php echo $this->settings['contact'][$this->session->public_lang_code]['office']; ?></p>
                            <p><b>Hotline:&nbsp;</b><?php echo $this->settings['contact'][$this->session->public_lang_code]['phone']; ?></p>
                            <p><b>Email:&nbsp;</b><?php echo $this->settings['contact'][$this->session->public_lang_code]['email']; ?></p>
                        </div>
                        <div class="col-12 col-md-5">
                            <p>Bạn cần tư vấn về sản phẩm?</p>
                            <h2>Hãy để lại số điện thoại</h2>
                            <p>Chúng tôi sẽ liên hệ lại với bạn ngay</p>
                            <form id="form_footer" class="">
                                <input class="col-12" type="text" name="fullname" placeholder="Họ & tên . . .">
                                <input class="col-12" type="text" name="phone" placeholder="Số điện thoại . . .">
                                <button type="submit" class="buttonls" id="btn_footer"><img src="<?php echo base_url(); ?>public/images/common/send.svg"></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center" id="copyright">Copyright &nbsp;&nbsp;<a href="https://muabansach.site">ITbooks</a> -2019</div>
            </footer>
            <script type="text/javascript">
                $('#open-sidebar img').attr('src','<?php echo base_url(); ?>public/images/common/left.svg');
            </script>