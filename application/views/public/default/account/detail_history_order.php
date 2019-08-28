<section class="page-primary v2">
    <div class="container">
        <nav aria-label="breadcrumb">
          <?php echo !empty($breadcrumb)?$breadcrumb:''; ?>
      </nav>
      <div class="wrap-account">
        <div class="row">
            <div class="col-lg-3">
                <?php $this->load->view($this->template_path.'account/_list_menu', array('avatar' => '')); ?>
            </div>
            <div class="col-lg-9">
                <div class="ct-account">
                    <h3 class="title-acc v2">
                        <span><?php echo lang('detail_ordered'); ?> <strong>#<?php echo $data->code; ?></strong></span>
                        <?php if ($data->is_status == 1) :?>
                            <a href="<?php echo base_url('account/cancel_order/'.$data->id); ?>" title="<?php echo lang('cancer_the_entire_order'); ?>" class="cancel-order"><?php echo lang('cancer_the_entire_order'); ?></a>
                        <?php endif; ?>
                    </h3>
                    <div class="history-order-details">
                        <div class="top-order">
                            <div class="left">
                                <span class="status"><?php echo lang('is_status'); ?>: <strong><?php echo is_status_order($data->is_status); ?></strong></span>
                                <span class="time"><?php echo lang('order_time'); ?>: <?php echo formatDate($data->created_time); ?></span>
                            </div>
                            <span class="count"><?php echo lang('total'); ?>: <strong> <?php echo !empty($data->total) ? formatMoney($data->total).'đ' : '' ?></strong></span>
                        </div>
                        <div class="info-transfer">
                            <div class="row col-mar-12">
                                <div class="col-lg-6">
                                    <div class="block-transfer">
                                        <h4 class="title-transfer"><?php echo lang('receiver_info'); ?></h4>
                                        <div class="ct">
                                            <span class="name"><?php echo $data->full_name; ?></span>
                                            <p><?php echo lang('text_address'); ?>: <?php echo $data->address.', '.$data->ward_id.', '.$data->district_id.', '.$data->city_id; ?>, VN</p>
                                            <p><?php echo lang('text_phone'); ?>: <?php echo $data->phone; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="block-transfer">
                                        <h4 class="title-transfer"><?php echo lang('delivery_form'); ?></h4>
                                        <div class="ct">
                                            <p><?php echo lang('payment_on_delivery'); ?></p>
                                            <span>(<?php echo lang('tomita_will'); ?>)</span>
                                            <!-- <span>Chi phí vận chuyển : 25.000đ</span> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="md-cart-tb">
                            <table>
                                <thead>
                                    <tr>
                                        <th><?php echo lang('product'); ?></th>
                                        <th><?php echo lang('price'); ?></th>
                                        <th><?php echo lang('num_pr'); ?></th>
                                        <th colspan="2"><?php echo lang('provisional'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data->product_item)) foreach ($data->product_item as $key => $value) { ?>
                                        <tr>
                                            <td>
                                                <div class="if-pro-cart">
                                                    <a class="img" href="<?php echo getUrlProduct(['id'=>$value->product_id,'slug'=>$value->product->slug]); ?>" title="<?php echo $value->product->title; ?>">
                                                        <img src="<?php echo getImageThumb($value->product->thumbnail,90,89); ?>" alt="<?php echo getImageThumb($value->product->thumbnail,90,89); ?>" title="<?php echo $value->product->title; ?>">
                                                    </a>
                                                    <div class="ct">
                                                        <a class="smooth title" href="<?php echo getUrlProduct(['id'=>$value->product_id,'slug'=>$value->product->slug]); ?>" title="<?php echo $value->product->title; ?>"><?php echo $value->product->title; ?></a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo formatMoney($value->price).'đ'; ?> <del><?php echo $value->product->sale_up != 0 ? formatMoney($value->product->price).'đ' : ''; ?></del></td>
                                            <td>
                                                <?php echo $value->quantity; ?>
                                            </td>
                                            <td><?php echo formatMoney($value->price * $value->quantity); ?>đ</td>
                                            <td>
                                                <?php if ($data->is_status == 1) :?>
                                                <a href="<?php echo base_url('account/cancel_item/'.$data->id.'/'.$value->product->id); ?>" title="Hủy" class="cancel"><?php echo lang('text_cancel')?></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="md-cart-foot">
                            <div class="top">
                                <span class="total-provision"><?php echo lang('provisional'); ?>: <?php echo !empty($data->total) ?  formatMoney($data->total).'đ' : ''; ?></span>
                                <div class="total">
                                    <p><?php echo lang('total'); ?>: <strong><?php echo !empty($data->total) ? formatMoney($data->total).'đ' : ''; ?></strong></p>
                                    <span>(<?php echo lang('price_include'); ?>)</span>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo base_url('account/history_order'); ?>" title="Quay lại đơn hàng của tôi" class="btn-back-order"><i class="arrow_left"></i><span><?php echo lang('back_order'); ?></span></a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</section>