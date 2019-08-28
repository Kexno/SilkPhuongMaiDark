<section class="page-primary v2">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home'); ?></a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo lang('account_text_my_account'); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo lang('account_text_title'); ?></li>
            </ol>
        </nav>
        <div class="wrap-account">
            <div class="row">
                <div class="col-lg-3">
                    <?php $this->load->view($this->template_path . 'account/_list_menu'); ?>
                </div>
                <div class="col-lg-9">
                    <div class="ct-account">
                        <h3 class="title-acc"><?php echo lang('account_text_title') ?></h3>
                        <div class="form-account">
                            <form id="form_profile">
                                <div class="form-group">
                                    <span><?php echo lang('account_text_email') ?> <small>*</small> :</span>
                                    <div class="form-validation">
                                        <input type="email" name="email" class="form-control" placeholder="<?php echo lang('account_placeholder_email') ?>"
                                        value="<?php echo isset($oneAccount) && !empty($oneAccount->email) ? $oneAccount->email : '' ?>"
                                        disabled="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span><?php echo lang('account_text_name') ?> <small>*</small> :</span>
                                    <div class="form-validation">
                                        <input type="text" name="full_name" class="form-control" placeholder="<?php echo lang('account_placeholder_name') ?>"
                                        value="<?php echo isset($oneAccount) && !empty($oneAccount->full_name) ? $oneAccount->full_name : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span><?php echo lang('account_text_phone') ?> <small>*</small> :</span>
                                    <div class="form-validation">
                                        <input type="tel" name="phone" class="form-control" placeholder="<?php echo lang('account_placeholder_phone') ?>"
                                        value="<?php echo isset($oneAccount) && !empty($oneAccount->phone) ? $oneAccount->phone : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span><?php echo lang('account_text_date_of_birth') ?> :</span>
                                    <div class="form-validation">
                                        <input type="text" name="birthday" class="form-control birthday"
                                        value="<?php echo isset($oneAccount) && !empty($oneAccount->birthday) ? formatDate($oneAccount->birthday) : '' ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span><?php echo lang('account_text_gender') ?> :</span>
                                    <div class="ip-select right">
                                        <select class="form-control" name="gender">
                                            <option selected disabled hidden
                                            value=""><?php echo lang('account_text_gender') ?></option>
                                            <option <?php echo isset($oneAccount) && $oneAccount->gender == 1 ? 'selected' : '' ?>
                                            value="1">
                                            <?php echo lang('male') ?>
                                        </option>
                                        <option <?php echo isset($oneAccount) && $oneAccount->gender == 2 ? 'selected' : '' ?>
                                        value="2">
                                        <?php echo lang('female') ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <span><?php echo lang('account_text_job') ?> :</span>
                            <input type="text" class="form-control" name="job" placeholder="<?php echo lang('account_placeholder_job') ?>"
                            value="<?php echo isset($oneAccount) && !empty($oneAccount->job) ? $oneAccount->job : '' ?>">
                        </div>
                    </form>
                    <div class="form-group">
                        <span><?php echo lang('account_text_address') ?> :</span>
                        <div class="list-address" style="width: 100%">
                            <?php
                            if (isset($address)):
                                foreach ($address as $key => $value): ?>
                                    <div class="block-address">
                                        <div class="top">
                                            <div class="left">
                                                <span class="name"><?php echo $value->full_name ?></span>
                                                <?php if ($value->default): ?>
                                                    <span class="note"><i
                                                        class="icon_check_alt2"></i> <?php echo lang('account_text_address_default') ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="crt">
                                                    <a href="<?php echo base_url('account/detail/' . ($key + 1)) ?>"
                                                     title=""
                                                     class="edit"><?php echo lang('account_text_address_edit') ?></a>
                                                     <?php if (!$value->default): ?>
                                                        <a href="javascript:;"
                                                        title="" class="edit delete_address"
                                                        data-delete="<?php echo $key ?>"><?php echo lang('account_text_address_delete') ?></a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="address"><?php echo lang('account_text_address') ?>: <?php echo $value->full_address ?></div>
                                        </div>
                                    <?php endforeach;
                                endif; ?>
                                <a href="<?php echo base_url('account/detail') ?>" title="" class="btn-address"><i
                                    class="icon_plus"></i><?php echo lang('account_text_address_new') ?></a>
                                </div>
                            </div>
                            <div class="form-group">
                                <span class="empty">&nbsp;</span>
                                <button class="btn-account update_profile" type="button"><?php echo lang('account_text_update') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>