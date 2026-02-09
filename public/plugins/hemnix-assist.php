<?php
/**
 * Plugin Name: Hemnix Assist – AI Chatbot
 * Plugin URI: https://www.hemnix.com
 * Description: Easily add the Hemnix Assist AI chatbot to your WordPress website.
 * Version: 1.0.0
 * Author: Hemnix Technologies Pvt. Ltd.
 * Author URI: https://www.hemnix.com
 * License: GPL v2 or later
 */

if (!defined('ABSPATH')) {
    exit;
}

class Hemnix_Assist_Chatbot {

    const OPTION_KEY = 'hemnix_assist_settings';

    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('wp_footer', [$this, 'inject_chatbot_script']);
    }

    /**
     * Add settings page
     */
    public function add_settings_page() {
        add_options_page(
            'Hemnix Assist',
            'Hemnix Assist',
            'manage_options',
            'hemnix-assist',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting(
            'hemnix_assist_group',
            self::OPTION_KEY,
            [$this, 'sanitize_settings']
        );
    }

    /**
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        return [
            'enabled' => isset($input['enabled']) ? 1 : 0,
            'public_key' => sanitize_text_field($input['public_key'] ?? ''),
        ];
    }

    /**
     * Render admin settings page
     */
    public function render_settings_page() {
        $settings = get_option(self::OPTION_KEY);
        ?>
        <div class="wrap">
            <h1>Hemnix Assist – AI Chatbot</h1>

            <form method="post" action="options.php">
                <?php settings_fields('hemnix_assist_group'); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row">Enable Chatbot</th>
                        <td>
                            <label>
                                <input type="checkbox" name="<?php echo self::OPTION_KEY; ?>[enabled]" value="1"
                                    <?php checked(1, $settings['enabled'] ?? 0); ?>>
                                Enable Hemnix Assist chatbot on your website
                            </label>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Public Chatbot Key</th>
                        <td>
                            <input type="text"
                                   name="<?php echo self::OPTION_KEY; ?>[public_key]"
                                   value="<?php echo esc_attr($settings['public_key'] ?? ''); ?>"
                                   class="regular-text"
                                   placeholder="Enter your public chatbot key">
                            <p class="description">
                                Get this key from your Hemnix Assist dashboard.
                            </p>
                        </td>
                    </tr>
                </table>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Inject chatbot script into frontend
     */
    public function inject_chatbot_script() {
        if (is_admin()) {
            return;
        }

        $settings = get_option(self::OPTION_KEY);

        if (empty($settings['enabled']) || empty($settings['public_key'])) {
            return;
        }

        $public_key = esc_attr($settings['public_key']);
        ?>
        <!-- Hemnix Assist Chatbot -->
        <script
            src="https://chatbot.hemnix.com/widget.js"
            data-key="<?php echo $public_key; ?>"
            async>
        </script>
        <!-- End Hemnix Assist Chatbot -->
        <?php
    }
}

new Hemnix_Assist_Chatbot();
