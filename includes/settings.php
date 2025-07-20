<?php
if (!defined('ABSPATH')) exit;

class PostToSendy_Settings {
    private $options;

    public function __construct() {
        add_action('admin_menu', [$this, 'add_plugin_page']);
        add_action('admin_init', [$this, 'page_init']);
    }

    public function add_plugin_page() {
        add_options_page(
            __('Post to Sendy Settings', 'post-to-sendy'),
            __('Post to Sendy', 'post-to-sendy'),
            'manage_options',
            'post2sendy-admin',
            [$this, 'create_admin_page']
        );
    }

    public function create_admin_page() {
        $this->options = get_option('post2sendy_options');
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Post to Sendy Settings', 'post-to-sendy'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('post2sendy_option_group');
                do_settings_sections('post2sendy-admin');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function page_init() {
        register_setting(
            'post2sendy_option_group',
            'post2sendy_options',
            [$this, 'sanitize']
        );

        add_settings_section(
            'post2sendy_section',
            __('Sendy Integration Settings', 'post-to-sendy'),
            null,
            'post2sendy-admin'
        );

        add_settings_field(
            'api_url',
            __('Sendy API URL', 'post-to-sendy'),
            [$this, 'api_url_callback'],
            'post2sendy-admin',
            'post2sendy_section'
        );

        add_settings_field(
            'api_key',
            __('Sendy API Key', 'post-to-sendy'),
            [$this, 'api_key_callback'],
            'post2sendy-admin',
            'post2sendy_section'
        );
    }

    public function sanitize($input) {
        $sanitized = [];
        $sanitized['api_url'] = isset($input['api_url']) ? esc_url_raw($input['api_url']) : '';
        $sanitized['api_key'] = isset($input['api_key']) ? sanitize_text_field($input['api_key']) : '';
        return $sanitized;
    }

    public function api_url_callback() {
        printf(
            '<input type="text" id="api_url" name="post2sendy_options[api_url]" value="%s" class="regular-text" />',
            isset($this->options['api_url']) ? esc_attr($this->options['api_url']) : ''
        );
    }

    public function api_key_callback() {
        printf(
            '<input type="text" id="api_key" name="post2sendy_options[api_key]" value="%s" class="regular-text" />',
            isset($this->options['api_key']) ? esc_attr($this->options['api_key']) : ''
        );
    }
}
