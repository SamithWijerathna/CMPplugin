<?php
class AIO_CMP_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Coming Soon Settings',
            'Coming Soon',
            'manage_options',
            'aio-cmp-settings',
            array($this, 'settings_page'),
            'dashicons-clock'
        );
    }
    
    public function settings_init() {
        register_setting('aio_cmp', 'aio_cmp_settings', array($this, 'sanitize_settings'));
        register_setting('aio_cmp', 'aio_cmp_active');
        
        // General Section
        add_settings_section(
            'aio_cmp_general', 
            'General Settings', 
            array($this, 'general_section_callback'), 
            'aio_cmp'
        );
        
        add_settings_field(
            'aio_cmp_active', 
            'Coming Soon Status',
            array($this, 'active_callback'),
            'aio_cmp',
            'aio_cmp_general'
        );
        
        // Content Section
        add_settings_section(
            'aio_cmp_content', 
            'Content Settings', 
            array($this, 'content_section_callback'), 
            'aio_cmp'
        );
        
        add_settings_field(
            'title', 
            'Page Title',
            array($this, 'title_callback'), 
            'aio_cmp',
            'aio_cmp_content'
        );
        
        add_settings_field(
            'subtitle', 
            'Subtitle',
            array($this, 'subtitle_callback'), 
            'aio_cmp',
            'aio_cmp_content'
        );
        
        // Design Section
        add_settings_section(
            'aio_cmp_design', 
            'Design Customization', 
            array($this, 'design_section_callback'), 
            'aio_cmp'
        );
        
        add_settings_field(
            'bg_color', 
            'Background Color',
            array($this, 'bg_color_callback'), 
            'aio_cmp',
            'aio_cmp_design'
        );
        
        add_settings_field(
            'text_color', 
            'Text Color',
            array($this, 'text_color_callback'), 
            'aio_cmp',
            'aio_cmp_design'
        );
        
        add_settings_field(
            'accent_color', 
            'Accent Color',
            array($this, 'accent_color_callback'), 
            'aio_cmp',
            'aio_cmp_design'
        );
    }
    
    public function sanitize_settings($input) {
        $sanitized = array();
        
        // Sanitize text fields
        if(isset($input['title'])) {
            $sanitized['title'] = sanitize_text_field($input['title']);
        }
        
        if(isset($input['subtitle'])) {
            $sanitized['subtitle'] = sanitize_text_field($input['subtitle']);
        }
        
        // Sanitize color fields
        if(isset($input['bg_color'])) {
            $sanitized['bg_color'] = sanitize_hex_color($input['bg_color']);
        }
        
        if(isset($input['text_color'])) {
            $sanitized['text_color'] = sanitize_hex_color($input['text_color']);
        }
        
        if(isset($input['accent_color'])) {
            $sanitized['accent_color'] = sanitize_hex_color($input['accent_color']);
        }
        
        return $sanitized;
    }
    
    // Section callbacks
    public function general_section_callback() {
        echo '<p>Enable or disable the coming soon mode for your site.</p>';
    }
    
    public function content_section_callback() {
        echo '<p>Customize the content displayed on your coming soon page.</p>';
    }
    
    public function design_section_callback() {
        echo '<p>Customize the appearance of your coming soon page.</p>';
    }
    
    // Field callbacks
    public function active_callback() {
        $active = get_option('aio_cmp_active', 0);
        echo '<label class="switch">';
        echo '<input type="checkbox" name="aio_cmp_active" value="1" ' . checked(1, $active, false) . ' />';
        echo '<span class="slider round"></span></label>';
        echo '<span class="description">Enable coming soon mode</span>';
    }
    
    public function title_callback() {
        $options = get_option('aio_cmp_settings', array());
        $title = isset($options['title']) ? $options['title'] : get_bloginfo('name');
        echo '<input type="text" class="regular-text" name="aio_cmp_settings[title]" value="' . esc_attr($title) . '" />';
        echo '<p class="description">The main heading on your coming soon page. Defaults to your site title.</p>';
    }
    
    public function subtitle_callback() {
        $options = get_option('aio_cmp_settings', array());
        $subtitle = isset($options['subtitle']) ? $options['subtitle'] : 'Site Under Construction';
        echo '<input type="text" class="regular-text" name="aio_cmp_settings[subtitle]" value="' . esc_attr($subtitle) . '" />';
        echo '<p class="description">The text that appears below the main title.</p>';
    }
    
    public function bg_color_callback() {
        $options = get_option('aio_cmp_settings', array());
        $bg_color = isset($options['bg_color']) ? $options['bg_color'] : '#0c0c1d';
        echo '<input type="color" name="aio_cmp_settings[bg_color]" value="' . esc_attr($bg_color) . '" class="aio-cmp-color-picker" data-default-color="#0c0c1d" />';
    }
    
    public function text_color_callback() {
        $options = get_option('aio_cmp_settings', array());
        $text_color = isset($options['text_color']) ? $options['text_color'] : '#ffffff';
        echo '<input type="color" name="aio_cmp_settings[text_color]" value="' . esc_attr($text_color) . '" class="aio-cmp-color-picker" data-default-color="#ffffff" />';
    }
    
    public function accent_color_callback() {
        $options = get_option('aio_cmp_settings', array());
        $accent_color = isset($options['accent_color']) ? $options['accent_color'] : '#ff6b6b';
        echo '<input type="color" name="aio_cmp_settings[accent_color]" value="' . esc_attr($accent_color) . '" class="aio-cmp-color-picker" data-default-color="#ff6b6b" />';
    }
    
    public function settings_page() {
        // Get current settings
        $options = get_option('aio_cmp_settings', array());
        $active = get_option('aio_cmp_active', 0);
        ?>
        <div class="wrap aio-cmp-admin">
            <h1><span class="dashicons dashicons-clock"></span> AllInOne Coming Soon</h1>
            
            <div class="aio-cmp-container">
                <div class="aio-cmp-settings">
                    <form method="post" action="options.php">
                        <?php
                        settings_fields('aio_cmp');
                        do_settings_sections('aio_cmp');
                        submit_button('Save Settings');
                        ?>
                    </form>
                </div>
                
                <div class="aio-cmp-preview">
                    <h2>Live Preview</h2>
                    <div class="aio-cmp-preview-container">
                        <iframe id="aio-cmp-preview-frame" src="<?php echo esc_url(add_query_arg('preview', 'cmp', home_url())); ?>"></iframe>
                    </div>
                    <p class="description">Changes will be visible in the preview after saving.</p>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function enqueue_admin_assets($hook) {
        // Only load on our settings page
        if($hook != 'toplevel_page_aio-cmp-settings') {
            return;
        }
        
        // Add color picker
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        // Admin styles
        wp_enqueue_style(
            'aio-cmp-admin', 
            AIO_CMP_URL . 'assets/css/admin.css', 
            array(), 
            filemtime(AIO_CMP_PATH . 'assets/css/admin.css')
        );
        
        // Admin script
        wp_enqueue_script(
            'aio-cmp-admin',
            AIO_CMP_URL . 'assets/js/admin.js',
            array('jquery', 'wp-color-picker'),
            filemtime(AIO_CMP_PATH . 'assets/js/admin.js'),
            true
        );
    }
}