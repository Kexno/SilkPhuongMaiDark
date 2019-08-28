<h3 class="title-cart"><?php echo lang('your_cart'); ?><?php if(!empty($data_cart)){ ?> <span>(<?php echo !empty($data_total)?$data_total:''; ?> <?php echo lang('product'); ?>)</span><?php } ?></h3>
<div class="md-cart-tb">
    <?php if(!empty($data_cart)){ ?>
        <table>
            <thead>
                <tr>
                    <th><?php echo lang('cart_product'); ?></th>
                    <th><?php echo lang('cart_price'); ?></th>
                    <th><?php echo lang('cart_quantity'); ?></th>
                    <th><?php echo lang('cart_into_money'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data_cart as $key => $value) { ?>
                    <tr>
                        <td>
                            <div class="if-pro-cart">
                                <a class="img" href="javascript:void(0)" title="">
                                    <img src="<?php echo getImageThumb($value['options']['thumbnail'],90,89); ?>" alt="<?php echo getImageThumb($value['options']['thumbnail'],90,89); ?>" title="<?php echo $value['name'] ; ?>">
                                </a>
                                <div class="ct">
                                    <a class="smooth title" href="<?php echo getUrlProduct(['id' => $value['id']]) ?>" title="<?php echo $value['name'] ; ?>"><?php echo $value['name'] ; ?></a>
                                    <a class="smooth remove" href="javascript:;" title="" onclick="handingCart('<?php echo $value['rowid']; ?>','delete');"><i class="icon_close"></i> <?php echo lang('discard_the_product'); ?></a>
                                </div>
                            </div>
                        </td>
                        <td><?php echo formatMoney($value['price']); ?>đ <?php if ($value['options']['sale_up'] > 0):?> <del><?php echo formatMoney($value['options']['price']); ?>đ</del> <?php endif;?></td>
                        <td>
                            <div class="i-number">
                                <button class="n-ctrl down smooth" data-type="minus"></button>
                                <input type="text" class="numberic" name="numberic_pr" data_id="<?php echo $value['rowid']; ?>" min="1" max="1000" value="<?php echo $value['qty']; ?>">
                                <button class="n-ctrl up smooth" data-type="plus"></button>
                            </div>
                        </td>
                        <td><?php echo formatMoney($value['subtotal']); ?>đ</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php }else{ ?>
        <h4 style="text-align: center;"><?php echo lang('cart_empty'); ?></h4>
    <?php } ?>
</div>
<div class="md-cart-foot">
    <?php if(!empty($data_cart)){ ?>
        <div class="top">
            <span class="total-provision"><?php echo lang('provisional'); ?>: <?php echo formatMoney(!empty($data_total_price)?$data_total_price:''); ?>đ</span>
            <div class="total">
                <p><?php echo lang('total'); ?>: <strong><?php echo formatMoney(!empty($data_total_price)?$data_total_price:''); ?>đ</strong></p>
                <span>(<?php echo lang('price_include'); ?>)</span>
            </div>
        </div>
    <?php } ?>
    <div class="bottom">
        <div class="cell">
            <a class="smooth ctrl-continue" href="javascript:void(0)" title="" data-dismiss="modal"><i class="arrow_left"></i> <?php echo lang('continue_buying'); ?></a>
        </div>
        <?php if(!empty($data_cart)){ ?>
            <div class="cell">
                <a class="smooth ctrl-payment" href="<?php echo base_url('cart/payment'); ?>" title="Gửi đơn hàng" ><?php echo lang('cart_send_order'); ?></a>
            </div>
        <?php } ?>
    </div>
</div>