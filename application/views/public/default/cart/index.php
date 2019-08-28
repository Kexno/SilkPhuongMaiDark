<section class="page-primary v2">
    <div class="container">
        <nav aria-label="breadcrumb">
<!--             <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Tài khoản</a></li>
                <li class="breadcrumb-item active" aria-current="page">Thông tin tài khoản</li>
            </ol> -->
        </nav>
        <div class="wrap-account v2">
            <div class="row">
                <div class="col-lg-7">
                    <h3 class="title-payment">
                        <?php echo lang('order_info'); ?>
                        <?php if(empty($account)){ ?>
                            <p class="note"><?php echo lang('have_account'); ?><a href="javascript:;" title="" data-toggle="modal" data-target="#pu-login" onclick="resetForm();"> <?php echo lang('login'); ?> </a><?php echo lang('for_faster_payment'); ?> </p>
                        <?php } ?>
                    </h3>
                    <form id="form_order">
                        <div class="form-transfer">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="<?php echo lang('text_fullname')?>" value="<?php echo !empty($account) ? (!empty($account->address) ? $account->address->full_name : $account->full_name) : ''; ?>" name="full_name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="<?php echo lang('shipping_address') ?>" value="<?php echo !empty($account) ? (!empty($account->address) ?  $account->address->address : '') : '' ?>" name="address">
                            </div>
                            <div class="form-group">
                                <input type="tel" class="form-control" placeholder="<?php echo lang('text_phone') ?>" value="<?php echo !empty($account) ? (!empty($account->address) ?  $account->address->phone : $account->phone) : '' ?>" name="phone">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="<?php echo lang('text_email') ?>" value="<?php echo !empty($account) ? $account->email : '' ?>" name="email">
                            </div>
                            <div class="form-group sl">
                                <select class="form-control" id="province" name="city_id">
                                    <option value="" disabled selected
                                            hidden><?php echo lang('account_placeholder_province') ?>
                                    </option>
                                    <?php if (isset($province)):
                                        foreach ($province as $value):?>
                                            <option <?php echo isset($account) && !empty($account->address) && $account->address->province == $value->id ? 'selected' : '' ?>
                                                    value="<?php echo $value->id ?>"><?php echo $value->name_with_type ?></option>
                                        <?php endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="form-group sl">
                                <select class="form-control" id="district" name="district_id">
                                    <option value="" disabled selected
                                            hidden><?php echo lang('account_placeholder_district') ?>
                                    </option>
                                    <?php if (isset($district)):
                                        foreach ($district as $value):?>
                                            <option <?php echo isset($account) && !empty($account->address) && $account->address->district == $value->id ? 'selected' : '' ?>
                                                    value="<?php echo $value->id ?>"><?php echo $value->name_with_type ?></option>
                                        <?php endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="form-group sl">
                                <select class="form-control" id="ward" name="ward_id">
                                    <option value="" disabled selected
                                            hidden><?php echo lang('account_placeholder_ward') ?>
                                    </option>
                                    <?php if (isset($ward)):
                                        foreach ($ward as $value):?>
                                            <option <?php echo isset($account) && !empty($account->address) && $account->address->ward == $value->id ? 'selected' : '' ?>
                                                    value="<?php echo $value->id ?>"><?php echo $value->name_with_type ?></option>
                                        <?php endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" rows="5" placeholder="<?php echo lang('order_notes') ?>" name="note"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-5">
                    <h3 class="title-payment"><?php echo lang('order_info_re'); ?></h3>
                    <div class="cart-not-login">
                        <div class="list">
                            <?php if(!empty($content_cart)) foreach ($content_cart as $key => $value) { ?>
                                <div class="item">
                                    <div class="if-pro">
                                        <a href="<?php echo getUrlProduct(['id'=>$value['id'],'slug'=>$value['options']['slug']]); ?>" title="<?php echo $value['name']; ?>" class="img"><img src="<?php echo getImageThumb($value['options']['thumbnail'],46,45); ?>" alt="<?php echo $value['options']['thumbnail']; ?>"><span class="count"><?php echo $value['qty']; ?></span></a>
                                        <a href="<?php echo getUrlProduct(['id'=>$value['id'],'slug'=>$value['options']['slug']]); ?>" title="<?php echo $value['name']; ?>" class="name"><?php echo $value['name']; ?></a>
                                    </div>
                                    <span class="price"><?php echo formatMoney($value['subtotal']); ?>đ</span>
                                </div>
                            <?php } ?>
                        </div>
                        <a href="javascript:;" onclick="loadCart();" class="change-cart"><?php echo lang('change_info_cart'); ?></a>
                        <div class="total-cart">
                            <ul>
                                <li><span><?php echo lang('provisional'); ?>:</span><b><?php echo formatMoney($total_price); ?>đ</b></li>
                                <!-- <li><span>Phí giao hàng:</span><b>25.000đ</b></li> -->
                                <li><span><?php echo lang('total'); ?>:</span><strong><?php echo formatMoney($total_price); ?>đ</strong></li>
                            </ul>
                            <p class="note">(<?php echo lang('price_include'); ?>)</p>
                            <button id="send_order"><?php echo lang('btn_send_order'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
<script type="text/javascript">
    _valid_load_city_cart=true;
</script>
