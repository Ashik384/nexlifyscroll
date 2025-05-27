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
        add_settings_field('duration', __('Scroll to Top Duration (seconds)', 'nexlifyscroll'), [$this, 'render_duration_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('easing', __('Scroll to Top Easing', 'nexlifyscroll'), [$this, 'render_easing_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('button_color', __('Button Color', 'nexlifyscroll'), [$this, 'render_button_color_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('button_icon', __('Button Icon', 'nexlifyscroll'), [$this, 'render_button_icon_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('button_position', __('Button Position', 'nexlifyscroll'), [$this, 'render_button_position_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('progress_bar_enabled', __('Enable Progress Bar', 'nexlifyscroll'), [$this, 'render_progress_bar_enabled_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('progress_bar_color', __('Progress Bar Color', 'nexlifyscroll'), [$this, 'render_progress_bar_color_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('smooth_scroll_enabled', __('Enable MouseWheel Smooth Scroll', 'nexlifyscroll'), [$this, 'render_smooth_scroll_enabled_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('smooth_scroll_lerp', __('Smooth Scroll Lerp (0-1)', 'nexlifyscroll'), [$this, 'render_smooth_scroll_lerp_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('smooth_scroll_duration', __('Smooth Scroll Duration (seconds)', 'nexlifyscroll'), [$this, 'render_smooth_scroll_duration_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('smooth_scroll_wheel_multiplier', __('Smooth Scroll Wheel Multiplier', 'nexlifyscroll'), [$this, 'render_smooth_scroll_wheel_multiplier_field'], 'nexlifyscroll', 'nexlifyscroll_main');
        add_settings_field('smooth_scroll_easing', __('Smooth Scroll Easing', 'nexlifyscroll'), [$this, 'render_smooth_scroll_easing_field'], 'nexlifyscroll', 'nexlifyscroll_main');

        // Anchor Link Smooth Scrolling Section
        add_settings_section('nexlifyscroll_anchor', __('Anchor Link Smooth Scrolling', 'nexlifyscroll'), null, 'nexlifyscroll');
        add_settings_field('anchor_scroll_enabled', __('Enable Anchor Link Smooth Scrolling', 'nexlifyscroll'), [$this, 'render_anchor_scroll_enabled_field'], 'nexlifyscroll', 'nexlifyscroll_anchor');
        add_settings_field('link_selectors', __('Link Selector(s)', 'nexlifyscroll'), [$this, 'render_link_selectors_field'], 'nexlifyscroll', 'nexlifyscroll_anchor');
        add_settings_field('animation_duration', __('Animation Duration (seconds)', 'nexlifyscroll'), [$this, 'render_animation_duration_field'], 'nexlifyscroll', 'nexlifyscroll_anchor');
        add_settings_field('easing_function', __('Easing Function', 'nexlifyscroll'), [$this, 'render_easing_function_field'], 'nexlifyscroll', 'nexlifyscroll_anchor');
        add_settings_field('scroll_offset', __('Scroll Offset (pixels)', 'nexlifyscroll'), [$this, 'render_scroll_offset_field'], 'nexlifyscroll', 'nexlifyscroll_anchor');
        add_settings_field('dynamic_offset_selector', __('Dynamic Offset Selector', 'nexlifyscroll'), [$this, 'render_dynamic_offset_selector_field'], 'nexlifyscroll', 'nexlifyscroll_anchor');
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

    public function render_smooth_scroll_enabled_field() {
        $options = get_option('nexlifyscroll_options', []);
        ?>
        <input type="checkbox" name="nexlifyscroll_options[smooth_scroll_enabled]" value="1" <?php checked(1, !empty($options['smooth_scroll_enabled'])); ?>>
        <?php
    }

    public function render_smooth_scroll_lerp_field() {
        $options = get_option('nexlifyscroll_options', []);
        $lerp = $options['smooth_scroll_lerp'] ?? 0.1;
        ?>
        <input type="number" step="0.01" min="0" max="1" name="nexlifyscroll_options[smooth_scroll_lerp]" value="<?php echo esc_attr($lerp); ?>">
        <p class="description"><?php _e('Linear interpolation intensity (0-1). Lower values make scrolling smoother.', 'nexlifyscroll'); ?></p>
        <?php
    }

    public function render_smooth_scroll_duration_field() {
        $options = get_option('nexlifyscroll_options', []);
        $duration = $options['smooth_scroll_duration'] ?? 1.2;
        ?>
        <input type="number" step="0.1" min="0.1" max="5" name="nexlifyscroll_options[smooth_scroll_duration]" value="<?php echo esc_attr($duration); ?>">
        <p class="description"><?php _e('Duration of scroll animation (seconds). Used for anchor scrolls.', 'nexlifyscroll'); ?></p>
        <?php
    }

    public function render_smooth_scroll_wheel_multiplier_field() {
        $options = get_option('nexlifyscroll_options', []);
        $multiplier = $options['smooth_scroll_wheel_multiplier'] ?? 1;
        ?>
        <input type="number" step="0.1" min="0.1" max="5" name="nexlifyscroll_options[smooth_scroll_wheel_multiplier]" value="<?php echo esc_attr($multiplier); ?>">
        <p class="description"><?php _e('Multiplier for mouse wheel events. Higher values scroll faster.', 'nexlifyscroll'); ?></p>
        <?php
    }

    public function render_smooth_scroll_easing_field() {
        $options = get_option('nexlifyscroll_options', []);
        $easing = $options['smooth_scroll_easing'] ?? 'power2.out';
        ?>
        <select name="nexlifyscroll_options[smooth_scroll_easing]">
            <option value="power2.out" <?php selected($easing, 'power2.out'); ?>>Power2.out</option>
            <option value="power1.inOut" <?php selected($easing, 'power1.inOut'); ?>>Power1.inOut</option>
            <option value="expo.out" <?php selected($easing, 'expo.out'); ?>>Expo.out</option>
            <option value="linear" <?php selected($easing, 'linear'); ?>>Linear</option>
        </select>
        <p class="description"><?php _e('Easing function for anchor scrolls.', 'nexlifyscroll'); ?></p>
        <?php
    }

    // Anchor Link Smooth Scrolling Render Methods
    public function render_anchor_scroll_enabled_field() {
        $options = get_option('nexlifyscroll_options', []);
        ?>
        <input type="checkbox" name="nexlifyscroll_options[anchor_scroll_enabled]" value="1" <?php checked(1, !empty($options['anchor_scroll_enabled'])); ?>>
        <p class="description"><?php _e('Enable smooth scrolling for anchor links (e.g., <code><a href="#section"></code>).', 'nexlifyscroll'); ?></p>
        <?php
    }

    public function render_link_selectors_field() {
        $options = get_option('nexlifyscroll_options', []);
        $value = $options['link_selectors'] ?? 'a[href*=\'#\']:not([href=\'#\'])';
        ?>
        <input type="text" name="nexlifyscroll_options[link_selectors]" value="<?php echo esc_attr($value); ?>" class="regular-text">
        <p class="description"><?php _e('CSS selector(s) for links to trigger smooth scrolling (e.g., <code>a[href*=\'#\']:not([href=\'#\'])</code>). Use commas for multiple selectors.', 'nexlifyscroll'); ?></p>
        <?php
    }

    public function render_animation_duration_field() {
        $options = get_option('nexlifyscroll_options', []);
        $value = $options['animation_duration'] ?? 0.8;
        ?>
        <input type="number" step="0.1" min="0.3" max="1.5" name="nexlifyscroll_options[animation_duration]" value="<?php echo esc_attr($value); ?>" class="small-text">
        <p class="description"><?php _e('Duration of the scroll animation in seconds (e.g., 0.8 for 800ms).', 'nexlifyscroll'); ?></p>
        <?php
    }

    public function render_easing_function_field() {
        $options = get_option('nexlifyscroll_options', []);
        $value = $options['easing_function'] ?? 'power2.out';
        ?>
        <select name="nexlifyscroll_options[easing_function]" class="regular-text">
            <option value="power2.out" <?php selected($value, 'power2.out'); ?>>Power2.out</option>
            <option value="power1.inOut" <?php selected($value, 'power1.inOut'); ?>>Power1.inOut</option>
            <option value="expo.out" <?php selected($value, 'expo.out'); ?>>Expo.out</option>
        </select>
        <p class="description"><?php _e('Easing function for the scroll animation (e.g., <code>power2.out</code> for smooth easing).', 'nexlifyscroll'); ?></p>
        <?php
    }

    public function render_scroll_offset_field() {
        $options = get_option('nexlifyscroll_options', []);
        $value = $options['scroll_offset'] ?? 0;
        ?>
        <input type="number" step="10" min="0" max="150" name="nexlifyscroll_options[scroll_offset]" value="<?php echo esc_attr($value); ?>" class="small-text">
        <p class="description"><?php _e('Static offset in pixels for fixed headers (e.g., 80px).', 'nexlifyscroll'); ?></p>
        <?php
    }

    public function render_dynamic_offset_selector_field() {
        $options = get_option('nexlifyscroll_options', []);
        $value = $options['dynamic_offset_selector'] ?? '';
        ?>
        <input type="text" name="nexlifyscroll_options[dynamic_offset_selector]" value="<?php echo esc_attr($value); ?>" class="regular-text">
        <p class="description"><?php _e('CSS selector for a dynamic offset element (e.g., <code>#header</code>). The height of this element will be used as the offset. Leave blank to use static offset.', 'nexlifyscroll'); ?></p>
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
        $sanitized['smooth_scroll_enabled'] = !empty($input['smooth_scroll_enabled']) ? 1 : 0;
        $sanitized['smooth_scroll_lerp'] = floatval($input['smooth_scroll_lerp'] ?? 0.1);
        $sanitized['smooth_scroll_duration'] = floatval($input['smooth_scroll_duration'] ?? 1.2);
        $sanitized['smooth_scroll_wheel_multiplier'] = floatval($input['smooth_scroll_wheel_multiplier'] ?? 1);
        $sanitized['smooth_scroll_easing'] = sanitize_text_field($input['smooth_scroll_easing'] ?? 'power2.out');

        // Anchor Link Smooth Scrolling Sanitization
        $sanitized['anchor_scroll_enabled'] = !empty($input['anchor_scroll_enabled']) ? 1 : 0;
        $sanitized['link_selectors'] = sanitize_text_field($input['link_selectors'] ?? 'a[href*=\'#\']:not([href=\'#\'])');
        $sanitized['animation_duration'] = max(0.3, min(1.5, floatval($input['animation_duration'] ?? 0.8)));
        $sanitized['easing_function'] = in_array($input['easing_function'] ?? 'power2.out', ['power2.out', 'power1.inOut', 'expo.out']) ? $input['easing_function'] : 'power2.out';
        $sanitized['scroll_offset'] = max(0, min(150, intval($input['scroll_offset'] ?? 0)));
        $sanitized['dynamic_offset_selector'] = sanitize_text_field($input['dynamic_offset_selector'] ?? '');

        return $sanitized;
    }
}
?>