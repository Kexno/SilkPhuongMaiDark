<div class="container page-404">
  <div class="error-404">
    <div class="error-image"><img src="<?php echo $this->templates_assets ?>images/404.png" alt="404"></div>
    <p class="sub-heading-text-404"><?php echo lang('not_found') ?></p>
    <p class="go-back-home"><a href="<?php echo base_url(); ?>" data-wpel-link="internal"><?php echo lang('back_home') ?></a></p></div>
</div>
<style>
  .error-404{
    text-align: center;
    margin: 80px auto;
  }
  .sub-heading-text-404{
    font-size: 16px;
    margin-top: 20px;
  }
  .error-404 .go-back-home a {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 500;
    text-decoration: none;
    line-height: 1.5;
    position: relative;
    display: inline-block;
    padding-bottom: 1px;
    color: #1e73be;
    text-decoration: underline;
  }


</style>