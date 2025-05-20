<?php
class NexlifyScroll_Settings {
    private $options;

    public function __construct() {
        $this->options = get_option('nexlifyscroll_options', []);
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function add_settings_page() {
        add_options_page(
            __('NexlifyScroll Settings', 'nexlifyscroll'),
            'NexlifyScroll',
            'manage_options',
            'nexlifyscroll',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings() {
        register_setting('nexlifyscroll_options_group', 'nexlifyscroll_options', [$this, 'sanitize']);

        add_settings_section(
            'nexlifyscroll_scroll_top',
            __('Scroll-to-Top Settings', 'nexlifyscroll'),
            null,
            'nexlifyscroll_scroll_top'
        );

        add_settings_field(
            'scroll_top_enabled',
            __('Enable Scroll-to-Top', 'nexlifyscroll'),
            [$this, 'checkbox_field'],
            'nexlifyscroll_scroll_top',
            'nexlifyscroll_scroll_top',
            ['name' => 'scroll_top_enabled']
        );
        add_settings_field(
            'button_color',
            __('Button Color', 'nexlifyscroll'),
            [$this, 'color_field'],
            'nexlifyscroll_scroll_top',
            'nexlifyscroll_scroll_top',
            ['name' => 'button_color']
        );
        add_settings_field(
            'button_icon',
            __('Button Icon', 'nexlifyscroll'),
            [$this, 'media_field'],
            'nexlifyscroll_scroll_top',
            'nexlifyscroll_scroll_top',
            ['name' => 'button_icon']
        );
        add_settings_field(
            'button_position',
            __('Button Position', 'nexlifyscroll'),
            [$this, 'select_field'],
            'nexlifyscroll_scroll_top',
            'nexlifyscroll_scroll_top',
            [
                'name' => 'button_position',
                'options' => [
                    'bottom-right' => __('Bottom Right', 'nexlifyscroll'),
                    'bottom-left' => __('Bottom Left', 'nexlifyscroll'),
                    'bottom-center' => __('Bottom Center', 'nexlifyscroll')
                ]
            ]
        );
        add_settings_field(
            'easing',
            __('Easing Type', 'nexlifyscroll'),
            [$this, 'select_field'],
            'nexlifyscroll_scroll_top',
            'nexlifyscroll_scroll_top',
            [
                'name' => 'easing',
                'options' => [
                    'power1.out'     => 'Power1 Out',      // Smooth, quick
                    'power2.out'     => 'Power2 Out',      // Balanced and smooth (already in your list)
                    'power3.out'     => 'Power3 Out',      // More ease-out (already in your list)
                    'power4.out'     => 'Power4 Out',      // Dramatic ease-out
                    'expo.out'       => 'Expo Out',        // High-end, elegant deceleration (already in list)
                    'sine.out'       => 'Sine Out',        // Subtle and soft
                    'quad.out'       => 'Quad Out',        // Basic ease-out, simple and clean
                    'back.out(1.7)'  => 'Back Out',        // Snappy with slight overshoot
                    'elastic.out(1,0.3)' => 'Elastic Out', // Bouncy spring feel (already in list)
                    'bounce.out'     => 'Bounce Out'       // Playful bounce at end
                ]
            ]
        );
        add_settings_field(
            'duration',
            __('Animation Duration (seconds)', 'nexlifyscroll'),
            [$this, 'number_field'],
            'nexlifyscroll_scroll_top',
            'nexlifyscroll_scroll_top',
            ['name' => 'duration', 'min' => 0.1, 'max' => 2, 'step' => 0.1]
        );
    }

    public function sanitize($input) {
        $sanitized = [];
        $fields = [
            'scroll_top_enabled' => 'bool',
            'button_color' => 'string',
            'button_icon' => 'int',
            'button_position' => 'string',
            'easing' => 'string',
            'duration' => 'float'
        ];

        foreach ($fields as $key => $type) {
            if (isset($input[$key])) {
                switch ($type) {
                    case 'bool':
                        $sanitized[$key] = (bool) $input[$key];
                        break;
                    case 'string':
                        $sanitized[$key] = sanitize_text_field($input[$key]);
                        break;
                    case 'float':
                        $sanitized[$key] = floatval($input[$key]);
                        break;
                    case 'int':
                        $sanitized[$key] = intval($input[$key]);
                        break;
                }
            }
        }
        return $sanitized;
    }

    public function render_settings_page() {
        include NEXLIFYSCROLL_PATH . 'includes/admin/templates/settings-page.php';
    }

    public function enqueue_scripts($hook) {
        if ($hook !== 'settings_page_nexlifyscroll') {
            return;
        }
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_media();
        wp_enqueue_style('nexlifyscroll-admin', NEXLIFYSCROLL_URL . 'assets/css/admin.css', [], NEXLIFYSCROLL_VERSION);
        wp_enqueue_script('nexlifyscroll-admin', NEXLIFYSCROLL_URL . 'assets/js/admin.js', ['jquery', 'wp-color-picker'], NEXLIFYSCROLL_VERSION, true);
    }

    public function checkbox_field($args) {
        $name = $args['name'];
        $value = isset($this->options[$name]) ? $this->options[$name] : false;
        ?>
        <input type="checkbox" name="nexlifyscroll_options[<?php echo esc_attr($name); ?>]" value="1" <?php checked($value, 1); ?>>
        <?php
    }

    public function select_field($args) {
        $name = $args['name'];
        $options = $args['options'];
        $value = isset($this->options[$name]) ? $this->options[$name] : key($options);
        ?>
        <select name="nexlifyscroll_options[<?php echo esc_attr($name); ?>]">
            <?php foreach ($options as $key => $label) : ?>
                <option value="<?php echo esc_attr($key); ?>" <?php selected($value, $key); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    public function number_field($args) {
        $name = $args['name'];
        $value = isset($this->options[$name]) ? $this->options[$name] : 0;
        $min = isset($args['min']) ? $args['min'] : 0;
        $max = isset($args['max']) ? $args['max'] : 100;
        $step = isset($args['step']) ? $args['step'] : 1;
        ?>
        <input type="number" name="nexlifyscroll_options[<?php echo esc_attr($name); ?>]" value="<?php echo esc_attr($value); ?>" min="<?php echo esc_attr($min); ?>" max="<?php echo esc_attr($max); ?>" step="<?php echo esc_attr($step); ?>">
        <?php
    }

    public function color_field($args) {
        $name = $args['name'];
        $value = isset($this->options[$name]) ? $this->options[$name] : '#000000';
        ?>
        <input type="text" name="nexlifyscroll_options[<?php echo esc_attr($name); ?>]" value="<?php echo esc_attr($value); ?>" class="nexlifyscroll-color-picker">
        <?php
    }

    public function media_field($args) {
        $name = $args['name'];
        $value = isset($this->options[$name]) ? $this->options[$name] : '';
        $image = $value ? wp_get_attachment_url($value) : '';
        ?>
        <div class="nexlifyscroll-media-upload">
            <input type="hidden" name="nexlifyscroll_options[<?php echo esc_attr($name); ?>]" value="<?php echo esc_attr($value); ?>" class="nexlifyscroll-media-id">
            <img src="<?php echo esc_url($image); ?>" class="nexlifyscroll-media-preview" style="max-width: 100px; <?php echo $image ? '' : 'display:none;'; ?>">
            <button type="button" class="button nexlifyscroll-upload-button"><?php _e('Upload Image', 'nexlifyscroll'); ?></button>
            <button type="button" class="button nexlifyscroll-remove-button" style="<?php echo $value ? '' : 'display:none;'; ?>"><?php _e('Remove Image', 'nexlifyscroll'); ?></button>
        </div>
        <?php
    }
}
?>