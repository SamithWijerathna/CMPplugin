<?php
/*
Plugin Name: AllInOne Coming Soon
Plugin URI: https://allinoneholdings.com
Description: Advanced coming soon/maintenance mode plugin with live preview.
Version: 1.0
Author: AllInOneHoldings IT Department
Author URI: https://allinoneholdings.com
*/

// Basic security check
defined('ABSPATH') || exit;

// Define plugin constants
define('AIO_CMP_PATH', plugin_dir_path(__FILE__));
define('AIO_CMP_URL', plugin_dir_url(__FILE__));

// Core functionality
class AllInOneComingSoon {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'plugin_activation'));
    }
    
    public function init() {
        // Admin functionality
        if(is_admin()) {
            require_once AIO_CMP_PATH . 'admin-settings.php';
            new AIO_CMP_Admin();
        }
        
        // Frontend display
        if(!is_admin() && !current_user_can('manage_options')) {
            add_filter('template_include', array($this, 'coming_soon_template'));
        }
        
        // Preview mode for admins
        if(isset($_GET['preview']) && $_GET['preview'] === 'cmp' && current_user_can('manage_options')) {
            add_filter('template_include', array($this, 'coming_soon_template'));
        }
    }
    
    public function plugin_activation() {
        // Set default options on plugin activation
        if (!get_option('aio_cmp_settings')) {
            $defaults = array(
                'title' => get_bloginfo('name'),
                'subtitle' => 'Site Under Construction',
                'bg_color' => '#0c0c1d',
                'text_color' => '#ffffff',
                'accent_color' => '#ff6b6b'
            );
            update_option('aio_cmp_settings', $defaults);
        }
        
        // Default to inactive
        if (!get_option('aio_cmp_active')) {
            update_option('aio_cmp_active', 0);
        }
    }
    
    public function coming_soon_template($template) {
        if(get_option('aio_cmp_active') || (isset($_GET['preview']) && $_GET['preview'] === 'cmp')) {
            return AIO_CMP_PATH . 'coming-soon-page.php';
        }
        return $template;
    }
}

new AllInOneComingSoon();

// Admin bar toggle
add_action('admin_bar_menu', 'aio_cmp_admin_bar', 999);
function aio_cmp_admin_bar($wp_admin_bar) {
    if(current_user_can('manage_options')) {
        $status = get_option('aio_cmp_active') ? 'disable' : 'enable';
        $title = get_option('aio_cmp_active') ? 'Active' : 'Inactive';
        $wp_admin_bar->add_node(array(
            'id'    => 'aio-cmp-toggle',
            'title' => 'Coming Soon: ' . $title,
            'href'  => admin_url('admin.php?page=aio-cmp-settings'),
            'meta'  => array('class' => 'aio-cmp-toggle')
        ));
    }
}