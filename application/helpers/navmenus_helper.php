<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function topNavBar($classname = '', $id = '', $submenuclass = '')
{
    return menus(1, $classname, $id, $submenuclass);
}

function topNavBarFooter($classname = '', $id = '', $submenuclass = '')
{
    return menus(2, $classname, $id, $submenuclass);
}

if (!function_exists('menus')) {
    function menus($location, $classname, $id, $submenuclass)
    {
        $ci =& get_instance();
        $ci->load->model('menus_model');
        $ci->load->library('NavsMenu');
        $ci->load->helper('link');
        $menuModel = new Menus_model();
        $q = $menuModel->getMenu($location, $ci->session->public_lang_code);
        $menuModel->listmenu($q);
        $listMenu = $menuModel->listmenu;
        $navsMenu = new NavsMenu();
        $navsMenu->set_items($listMenu);
        $config["nav_tag_open"] = "<ul id='$id' class='navbar-nav'>";
        //$config["parent_tag_open"] = "<li class='%s'>";
        //$config["item_anchor"]          = "<a href=\"%s\" class='smooth' title=\"%s\">%s</a>";
        //$config["parent_anchor"]          = "<a href=\"%s\" class='smooth' title=\"%s\">%s</a>";
        //$config["item_active_class"] = "active";
        //$config["children_tag_open"] = "<ul class='$submenuclass'>";
        $navsMenu->initialize($config);
        $menuHtml = $navsMenu->render();
        return $menuHtml;
    }
}
