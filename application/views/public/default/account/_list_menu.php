<?php
$url = $this->uri->uri_string();
?>
<div class="sb-account">
    <h3 class="title"><img style="width: 50px; height: 50px;"
                           src="<?php echo isset($this->session->userdata('avatar')['oauth_provider']) && !empty($this->session->userdata('avatar')['oauth_provider']) ? $this->session->userdata('avatar')['avatar'] : ((isset($this->session->userdata('avatar')['avatar']) && !empty($this->session->userdata('avatar')['avatar'])) ? getImageThumb($this->session->userdata('avatar')['avatar'], 50, 50, true, '100%') : getImageThumb('ic-user.jpg', 50, 50, '100%')) ?>"
                           alt="<?php echo lang('account_text_my_account'); ?>"><?php echo lang('account_text_my_account'); ?>
    </h3>
    <ul>
        <li class="<?php echo $url == 'account' || $url == 'account/detail' ? 'active' : '' ?>"><a
                    href="<?php echo base_url('account') ?>" title=""><i
                        class="lnr lnr-user"></i><?php echo lang('account_text_title') ?></a></li>
        <li class="<?php echo strpos($url, 'detail_history_order') || strpos($url, 'history_order') ? 'active' : '' ?>"><a href="<?php echo base_url('account/history_order'); ?>" title=""><i class="lnr lnr-history"></i><?php echo lang('account_text_order_history') ?></a></li>
        <li><a href="<?php echo base_url('account/logout'); ?>" title=""><i class="lnr lnr-exit"></i><?php echo lang('logout') ?></a>
        </li>
    </ul>
</div>
