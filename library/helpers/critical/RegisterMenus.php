<?php

if (!defined('ABSPATH')) {
    exit;
}

class RegisterMenus
{
    public function __construct()
    {
        register_nav_menus(array(
            'primary_menu'          => __('Main Menu', 'wdd'),
            'footer_menu'           => __('Footer Menu', 'wdd'),
        ));
    }
}
