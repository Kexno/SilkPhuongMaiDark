<section class="page-primary v2">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home'); ?></a></li>
                <li class="breadcrumb-item"><a href="<?php echo base_url('account')?>"><?php echo lang('account_text_my_account'); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo lang('account_text_title'); ?></li>
            </ol>
        </nav>
        <div class="wrap-account">
            <div class="row">
                <div class="col-lg-3">
                    <?php $this->load->view($this->template_path . 'account/_list_menu') ?>
                </div>
                <div class="col-lg-9">
                    <form id="address_form">
                        <div class="ct-account">
                            <h3 class="title-acc"><?php echo isset($detail_title) ? $detail_title : lang('account_detail_add_title') ?></h3>
                            <div class="form-account">
                                <form name="form_address">
                                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                                    <div class="form-group">
                                        <span><?php echo lang('account_text_name') ?> <small>*</small> :</span>
                                        <div class="form-validation">
                                            <input type="text" class="form-control"
                                                   value="<?php echo isset($oneAccount) ? $oneAccount->full_name : '' ?>"
                                                   name="full_name"
                                                   placeholder="<?php echo lang('account_placeholder_name') ?>">
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <span><?php echo lang('account_text_company') ?>:</span>
                                        <input type="text" class="form-control"
                                               value="<?php echo isset($oneAccount) && isset($oneAccount->company) ? $oneAccount->company : '' ?>"
                                               name="company"
                                               placeholder="<?php echo lang('account_placeholder_company') ?>">
                                    </div>
                                    <div class="form-group">
                                        <span><?php echo lang('account_text_phone') ?> <small>*</small> :</span>
                                        <div class="form-validation">
                                            <input type="tel" class="form-control"
                                                   value="<?php echo isset($oneAccount) ? $oneAccount->phone : '' ?>"
                                                   name="phone"
                                                   placeholder="<?php echo lang('account_placeholder_phone') ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <span><?php echo lang('account_text_province') ?> <small>*</small> :</span>
                                        <div class="form-validation">
                                            <div class="ip-select right">
                                                <select class="form-control" name="province" id="province">
                                                    <?php if (!isset($oneAccount) || !isset($oneAccount->province)): ?>
                                                        <option value="" disabled selected
                                                                hidden><?php echo lang('account_placeholder_province') ?>
                                                        </option>
                                                    <?php endif; ?>
                                                    <?php if (isset($province)):
                                                        foreach ($province as $value):?>
                                                            <option <?php echo isset($oneAccount) && isset($oneAccount->province) && $oneAccount->province == $value->id ? 'selected' : '' ?>
                                                                    value="<?php echo $value->id ?>"><?php echo $value->name_with_type ?></option>
                                                        <?php endforeach; endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <span><?php echo lang('account_text_district') ?> <small>*</small> :</span>
                                        <div class="form-validation">
                                            <div class="ip-select right">
                                                <select class="form-control" name="district" id="district">
                                                    <?php if (!isset($oneAccount) || !isset($oneAccount->district)): ?>
                                                        <option value="" disabled selected
                                                                hidden><?php echo lang('account_placeholder_district') ?>
                                                        </option>
                                                    <?php endif; ?>
                                                    <?php if (isset($district)):
                                                        foreach ($district as $value):?>
                                                            <option <?php echo isset($oneAccount) && isset($oneAccount->district) && $oneAccount->district == $value->code ? 'selected' : '' ?>
                                                                    value="<?php echo $value->id ?>"><?php echo $value->name_with_type ?></option>
                                                        <?php endforeach; endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <span><?php echo lang('account_text_ward') ?> <small>*</small> :</span>
                                        <div class="form-validation">
                                            <div class="ip-select right">
                                                <select class="form-control" name="ward" id="ward">
                                                    <?php if (!isset($oneAccount) || !isset($oneAccount->ward)): ?>
                                                        <option value="" disabled selected
                                                                hidden><?php echo lang('account_placeholder_ward') ?>
                                                        </option>
                                                    <?php endif; ?>
                                                    <?php if (isset($ward)):
                                                        foreach ($ward as $value):?>
                                                            <option <?php echo isset($oneAccount) && isset($oneAccount->ward) && $oneAccount->ward == $value->id ? 'selected' : '' ?>
                                                                    value="<?php echo $value->id ?>"><?php echo $value->name_with_type ?></option>
                                                        <?php endforeach; endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <span>Địa chỉ :</span>
                                        <textarea name="address" rows="3"
                                                  class="form-control"><?php echo isset($oneAccount) && isset($oneAccount->address) ? $oneAccount->address : '' ?></textarea>
                                    </div>
                                    <?php if (!isset($oneAccount) || isset($oneAccount->default) && $oneAccount->default == 0): ?>
                                        <div class="form-group">
                                            <span class="empty">&nbsp;</span>
                                            <label class="i-check">
                                                <input class="hidden" type="checkbox" name="default"><i></i>
                                                <span><?php echo lang('account_detail_text_default_address') ?></span>
                                            </label>
                                        </div>
                                    <?php else: ?>
                                        <input type="hidden" value="1" name="default">
                                    <?php endif; ?>
                                </form>
                                <div class="form-group">
                                    <span class="empty">&nbsp;</span>
                                    <button class="btn-account form_address"
                                            type="button"><?php echo isset($button_title) ? $button_title : lang('account_text_add') ?></button>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

        </div>
    </div>
</section>