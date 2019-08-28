<section class="page-primary v2">
    <div class="container">
        <nav aria-label="breadcrumb">
            <?php echo !empty($breadcrumbs)?$breadcrumbs:''; ?>
        </nav>
        <div class="wrap-account">
            <div class="row">
                <div class="col-lg-3">
                    <?php $this->load->view($this->template_path.'account/_list_menu', array('avatar' => '')); ?>
                </div>
                <div class="col-lg-9">
                    <div class="ct-account">
                        <h3 class="title-acc v2"><?php echo lang('purchase_order'); ?></h3>
                        <div class="history-order">
                            <?php if(!empty($data)){ ?>
                                <table class="table">
                                    <tr>
                                        <th><?php echo lang('code_orders'); ?></th>
                                        <th><?php echo lang('purchase_data'); ?></th>
                                        <th><?php echo lang('product'); ?></th>
                                        <th><?php echo lang('total'); ?></th>
                                        <th><?php echo lang('is_status'); ?></th>
                                    </tr>
                                    <?php foreach ($data as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><a href="<?php echo base_url('account/detail_history_order/'.$value->id); ?>" title="Chi tiết đơn hàng"><?php echo $value->code; ?></a></td>
                                            <td><?php echo formatDate($value->created_time); ?></td>
                                            <td><?php echo !empty($value->products) ? show_name_products($value->products) : ''; ?></td>
                                            <td><?php echo !empty($value->total) ? formatMoney($value->total).'đ' : ''; ?></td>
                                            <td><?php echo is_status_order($value->is_status); ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            <?php }else{ ?>
                                <div><?php echo lang('no_orders_yet'); ?></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>