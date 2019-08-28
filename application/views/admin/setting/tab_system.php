<div class="tab-pane" id="tab_system">
    <div class="box-body">
        <div class="form-group">
            <label><?php echo lang('form_email_admin'); ?></label>
            <input type="text" name="email_admin"
                   placeholder="Ví dụ: email@email.com, email2@email.com,..."
                   class="form-control"
                   value="<?php echo isset($email_admin) ? $email_admin : ''; ?>"/>
        </div>

        <div class="form-group">
            <label>Protocol</label>
            <input type="text" name="protocol" placeholder="Protocol" class="form-control"
                   value="<?php echo isset($protocol) ? $protocol : ''; ?>"/>
        </div>


        <div class="form-group">
            <label>SMTP Host</label>
            <input type="text" name="smtp_host" placeholder="SMTP Host" class="form-control"
                   value="<?php echo isset($smtp_host) ? $smtp_host : ''; ?>"/>
        </div>

        <div class="form-group">
            <label>SMTP User</label>
            <input type="text" name="smtp_user" placeholder="SMTP User" class="form-control"
                   value="<?php echo isset($smtp_user) ? $smtp_user : ''; ?>"/>
        </div>

        <div class="form-group">
            <label>SMTP Password</label>
            <input type="password" name="smtp_pass" placeholder="SMTP Password" class="form-control"
                   value="<?php echo isset($smtp_pass) ? $smtp_pass : ''; ?>"/>
        </div>

        <div class="form-group">
            <label>SMTP Port</label>
            <input type="text" name="smtp_port" placeholder="SMTP Port" class="form-control"
                   value="<?php echo isset($smtp_port) ? $smtp_port : ''; ?>"/>
        </div>
    </div>
</div>
