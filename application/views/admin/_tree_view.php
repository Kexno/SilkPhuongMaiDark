<ul class="treeview-menu">
    <?php
    if(!empty($childs)):
        foreach ($childs as $value):
        if (in_array($value['controller'], $permission) || $this->session->admin_group_id == 1):
            ?>
            <li class="<?php echo $value['class'] ?>">
                <?php
                if (count($value['children'])):
                ?>
                <a href="<?php echo $value['href'] ?>">
                    <?php
                    else:
                    ?>
                    <a href="<?php echo BASE_ADMIN_URL . $value['href'] ?>">
                        <?php

                        endif;
                        ?>
                        <i class="<?php echo $value['icon'] ?>"></i>
                        <span><?php echo $value['text'] ?></span>
                        <?php
                        if (count($value['children'])):
                            ?>
                            <span class="pull-right-container"><i
                                        class="fa fa-angle-left pull-right"></i></span>
                        <?php
                        endif;
                        ?>
                    </a>
                    <?php if (count($value['children'])>0) $this->load->view($this->template_path . '_tree_view',array('childs' => $value['children'], 'permission' => $permission));?>
            </li>

        <?php
        endif;
    endforeach;
    endif;
    ?>
</ul>