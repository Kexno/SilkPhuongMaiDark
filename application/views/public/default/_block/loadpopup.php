<!-- Popup Login -->
<div class="modal popup-login popup-primary fade" id="pu-login">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal"><i class="icon_close"></i></button>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="tabs-login">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" onclick="resetForm();" data-toggle="tab"
                               href="#login"><?php echo lang('login'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="resetForm();" data-toggle="tab"
                               href="#register"><?php echo lang('register'); ?></a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="login" class="tab-pane active">
                            <form id="formLogin">
                                <div class="fr-login">
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input type="text" name="email" class="form-control"
                                               placeholder="<?php echo lang('text_email') ?>" autofocus >
                                    </div>
                                    <div class="form-group">
                                        <label for=""><?php echo lang('text_password'); ?></label>
                                        <input type="password" name="password" class="form-control"
                                               placeholder="<?php echo lang('text_password') ?>">
                                    </div>
                                    <div class="qmk">
                                        <div class="save-acc">
                                            <label class="i-check">
                                                <input class="hidden" type="checkbox" name="remember"><i></i>
                                                <span><?php echo lang('remember_pass'); ?></span>
                                            </label>
                                        </div>
                                        <a href="javascript:;" title="Quên mật khẩu" class="forget-acc"
                                           id="btn_forget_pass"><?php echo lang('forgot'); ?></a>
                                    </div>
                                    <div class="btn-login">
                                        <button type="button" class="btn"
                                                onclick="login();"><?php echo lang('login'); ?></button>
                                    </div>
                                    <div class="other-ac">
                                        <div class="lb-or">
                                            <span><?php echo lang('login_with'); ?></span>
                                        </div>
                                        <ul>
                                            <li><a href="<?php echo base_url('auth/window/Facebook'); ?>"
                                                   title="Đăng nhập qua Facebook" class="smooth"><i
                                                            class="social_facebook"></i><span><?php echo lang('login_fb'); ?></span></a>
                                            </li>
                                            <li><a href="<?php echo base_url('auth/window/Google'); ?>"
                                                   title="Đăng nhập qua Google+" class="smooth"><i
                                                            class="social_googleplus"></i><span><?php echo lang('login_gg'); ?></span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="register" class="tab-pane fade">
                            <form id="formRegister">
                                <div class="fr-login">
                                    <div class="form-group">
                                        <label for=""><?php echo lang('text_fullname'); ?></label>
                                        <input name="full_name" type="text" class="form-control" placeholder="<?php echo lang('text_fullname') ?>" autofocus >
                                    </div>
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input name="email" type="email" class="form-control" placeholder="<?php echo lang('text_email') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for=""><?php echo lang('text_password'); ?></label>
                                        <input type="password" name="password" class="form-control" placeholder="<?php echo lang('text_password') ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for=""><?php echo lang('text_repassword'); ?></label>
                                        <input type="password" name="re-password" class="form-control" placeholder="<?php echo lang('text_repassword'); ?>">
                                    </div>
                                    <div class="btn-login">
                                        <button type="button" class="btn"
                                                onclick="register();"><?php echo lang('register'); ?></button>
                                    </div>
                                    <div class="other-ac">
                                        <div class="lb-or">
                                            <span><?php echo lang('login_with'); ?></span>
                                        </div>
                                        <ul>
                                            <li><a href="<?php echo base_url('auth/window/Facebook'); ?>"
                                                   title="Đăng nhập qua Facebook" class="smooth"><i
                                                            class="social_facebook"></i><span><?php echo lang('login_fb'); ?></span></a>
                                            </li>
                                            <li><a href="<?php echo base_url('auth/window/Google'); ?>"
                                                   title="Đăng nhập qua Google+" class="smooth"><i
                                                            class="social_googleplus"></i><span><?php echo lang('login_gg'); ?></span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Popup Login -->
<!-- popup forget password -->
<!-- Popup Login -->
<div class="modal popup-login popup-primary fade" id="forget_pass">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal"><i class="icon_close"></i></button>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="form-contact">
                    <div class="col-xs-12">
                        <h3 class="title-contact"><?php echo lang('forgot'); ?></h3>
                        <div class="form-group">
                            <input type="email" id="email_forget" class="form-control"
                                   placeholder="<?php echo lang('enter_email') ?>">
                        </div>
                        <div class="btn-contact">
                            <button id="send_forget"><?php echo lang('text_send'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- show password -->
<div class="modal popup-login popup-primary fade" id="show_pass">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal"><i class="icon_close"></i></button>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="form-contact">
                    <div class="col-xs-12">
                        <h3 class="title-contact"><?php echo lang('successful_authentication'); ?></h3>
                        <div class="form-group" style="text-align: center;">
                            <?php echo lang('new_password'); ?>: <span
                                    style="color: red;font-weight: bold;font-size: 16px;"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Popup Cart -->
<div class="modal popup-cart popup-primary fade" id="pu-cart">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal"><i class="icon_close"></i></button>
            <!-- Modal body -->
            <div class="modal-body" id="form_pop_cart">

            </div>
        </div>
    </div>
</div>
<div class="modal fade popup-product popup-primary" id="pu-product">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal"><i class="icon_close"></i></button>
            <!-- Modal body -->
            <div class="modal-body" id="popup_product">

            </div>
        </div>
    </div>
</div>
<div class="modal fade popup-primary popup-promotion">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal"><i class="icon_close"></i></button>
            <!-- Modal body -->
            <div class="modal-body" id="popup_promotion">

            </div>
        </div>
    </div>
</div>