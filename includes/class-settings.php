<?php
class NexlifyScroll_Settings {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function add_settings_page() {
        add_options_page(
            __('NexlifyScroll Settings', 'nexlifyscroll'),
            __('NexlifyScroll', 'nexlifyscroll'),
            'manage_options',
            'nexlifyscroll',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings() {
        register_setting('nexlifyscroll_options', 'nexlifyscroll_options', [$this, 'sanitize_options']);
        add_settings_section('nexlifyscroll_main', __('Main Settings', 'nexlifyscroll'), null, 'nexlifyscroll');
        add_settings_field('scroll_top_enabled', __('Enable Scroll to Top', 'nexlifyscroll'), [$this, 'render_scroll_top_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('duration', __('Scroll Duration (seconds)', 'nexlifyscroll'), [$this, 'render_duration_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('easing', __('Scroll Easing', 'nexlifyscroll'), [$this, 'render_easing_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('button_color', __('Button Color', 'nexlifyscroll'), [$this, 'render_button_color_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('button_icon', __('Button Icon', 'nexlifyscroll'), [$this, 'render_button_icon_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('button_position', __('Button Position', 'nexlifyscroll'), [$this, 'render_button_position_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('progress_bar_enabled', __('Enable Progress Bar', 'nexlifyscroll'), [$this, 'render_progress_bar_enabled_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('progress_bar_color', __('Progress Bar Color', 'nexlifyscroll'), [$this, 'render_progress_bar_color_field'], 'nexlifyscroll', 'nexlifyscroll_main');
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('NexlifyScroll Settings', 'nexlifyscroll'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('nexlifyscroll_options');
                do_settings_sections('nexlifyscroll');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function render_scroll_top_field() {
        $options = get_option('nexlifyscroll_options', []);
        ?>
        <input type="checkbox" name="nexlifyscroll_options[scroll_top_enabled]" value="1" <?php checked(1, !empty($options['scroll_top_enabled'])); ?>>
        <?php
    }

    public function render_duration_field() {
        $options = get_option('nexlifyscroll_options', []);
        $duration = $options['duration'] ?? 0.8;
        ?>
        <input type="number" step="0.1" min="0.1" max="2" name="nexlifyscroll_options[duration]" value="<?php echo esc_attr($duration); ?>">
        <?php
    }

    public function render_easing_field() {
        $options = get_option('nexlifyscroll_options', []);
        $easing = $options['easing'] ?? 'power2.out';
        ?>
        <select name="nexlifyscroll_options[easing]">
            <option value="power2.out" <?php selected($easing, 'power2.out'); ?>>Power2.out</option>
            <option value="power1.inOut" <?php selected($easing, 'power1.inOut'); ?>>Power1.inOut</option>
            <option value="expo.out" <?php selected($easing, 'expo.out'); ?>>Expo.out</option>
            <option value="bounce.out" <?php selected($easing, 'bounce.out'); ?>>Bounce.out</option>
        </select>
        <?php
    }

    public function render_button_color_field() {
        $options = get_option('nexlifyscroll_options', []);
        $color = $options['button_color'] ?? '#000000';
        ?>
        <input type="color" name="nexlifyscroll_options[button_color]" value="<?php echo esc_attr($color); ?>">
        <?php
    }

    public function render_button_icon_field() {
        $options = get_option('nexlifyscroll_options', []);
        $icon = $options['button_icon'] ?? '';
        ?>
        <input type="hidden" name="nexlifyscroll_options[button_icon]" id="button_icon" value="<?php echo esc_attr($icon); ?>">
        <input type="button" class="button button-secondary" value="<?php _e('Upload Icon', 'nexlifyscroll'); ?>" id="upload_icon_button">
        <img id="button_icon_preview" src="<?php echo esc_url($icon ? wp_get_attachment_url($icon) : ''); ?>" style="max-width: 100px; display: <?php echo $icon ? 'block' : 'none'; ?>;">
        <script>
            jQuery(document).ready(function($) {
                var frame;
                $('#upload_icon_button').on('click', function(e) {
                    e.preventDefault();
                    if (frame) {
                        frame.open();
                        return;
                    }
                    frame = wp.media({
                        title: '<?php _e('Select Icon', 'nexlifyscroll'); ?>',
                        multiple: false,
                        library: { type: 'image' },
                        button: { text: '<?php _e('Select', 'nexlifyscroll'); ?>' }
                    });
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        $('#button_icon').val(attachment.id);
                        $('#button_icon_preview').attr('src', attachment.url).show();
                    });
                    frame.open();
                });
            });
        </script>
        <?php
    }

    public function render_button_position_field() {
        $options = get_option('nexlifyscroll_options', []);
        $position = $options['button_position'] ?? 'bottom-right';
        ?>
        <select name="nexlifyscroll_options[button_position]">
            <option value="bottom-right" <?php selected($position, 'bottom-right'); ?>>Bottom Right</option>
            <option value="bottom-left" <?php selected($position, 'bottom-left'); ?>>Bottom Left</option>
        </select>
        <?php
    }

    public function render_progress_bar_enabled_field() {
        $options = get_option('nexlifyscroll_options', []);
        ?>
        <input type="checkbox" name="nexlifyscroll_options[progress_bar_enabled]" value="1" <?php checked(1, !empty($options['progress_bar_enabled'])); ?>>
        <?php
    }

    public function render_progress_bar_color_field() {
        $options = get_option('nexlifyscroll_options', []);
        $color = $options['progress_bar_color'] ?? '#ff0000';
        ?>
        <input type="color" name="nexlifyscroll_options[progress_bar_color]" value="<?php echo esc_attr($color); ?>">
        <?php
    }

    public function sanitize_options($input) {
        $sanitized = [];
        $sanitized['scroll_top_enabled'] = !empty($input['scroll_top_enabled']) ? 1 : 0;
        $sanitized['duration'] = floatval($input['duration'] ?? 0.8);
        $sanitized['easing'] = sanitize_text_field($input['easing'] ?? 'power2.out');
        $sanitized['button_color'] = sanitize_hex_color($input['button_color'] ?? '#000000');
        $sanitized['button_icon'] = absint($input['button_icon'] ?? 0);
        $sanitized['button_position'] = sanitize_text_field($input['button_position'] ?? 'bottom-right');
        $sanitized['progress_bar_enabled'] = !empty($input['progress_bar_enabled']) ? 1 : 0;
        $sanitized['progress_bar_color'] = sanitize_hex_color($input['progress_bar_color'] ?? '#ff0000');
        return $sanitized;
    }
}
?>