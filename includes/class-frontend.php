<?php
class NexlifyScroll_Frontend {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_footer', [$this, 'render_scroll_top']);
    }

    public function enqueue_scripts() {
        $options = get_option('nexlifyscroll_options', []);
        if (!empty($options['scroll_top_enabled']) || !empty($options['smooth_scroll_enabled'])) {
            wp_enqueue_script('gsap', NEXLIFYSCROLL_URL . 'assets/js/lib/gsap.min.js', [], '3.13.0', true);
            wp_enqueue_script('gsap-scrolltrigger', NEXLIFYSCROLL_URL . 'assets/js/lib/ScrollTrigger.min.js', ['gsap'], '3.13.0', true);
            wp_enqueue_script('gsap-scrollto', NEXLIFYSCROLL_URL . 'assets/js/lib/ScrollToPlugin.min.js', ['gsap'], '3.13.0', true);
            wp_enqueue_script('nexlifyscroll-frontend', NEXLIFYSCROLL_URL . 'assets/js/frontend.js', ['gsap', 'gsap-scrolltrigger', 'gsap-scrollto'], NEXLIFYSCROLL_VERSION, true);
            wp_enqueue_style('nexlifyscroll-frontend', NEXLIFYSCROLL_URL . 'assets/css/frontend.css', [], NEXLIFYSCROLL_VERSION);
            wp_localize_script('nexlifyscroll-frontend', 'nexlifyscrollSettings', [
                'scrollTop' => !empty($options['scroll_top_enabled']),
                'duration' => $options['duration'] ?? 0.8,
                'easing' => $options['easing'] ?? 'power2.out',
                'buttonColor' => $options['button_color'] ?? '#000000',
                'buttonIcon' => !empty($options['button_icon']) ? wp_get_attachment_url($options['button_icon']) : NEXLIFYSCROLL_URL . 'assets/images/default-icon.png',
                'buttonPosition' => $options['button_position'] ?? 'bottom-right',
                'progressBarEnabled' => !empty($options['progress_bar_enabled']),
                'progressBarColor' => $options['progress_bar_color'] ?? '#ff0000',
                'smoothScrollEnabled' => !empty($options['smooth_scroll_enabled']),
                'smoothScrollLerp' => $options['smooth_scroll_lerp'] ?? 0.1,
                'smoothScrollDuration' => $options['smooth_scroll_duration'] ?? 1.2,
                'smoothScrollWheelMultiplier' => $options['smooth_scroll_wheel_multiplier'] ?? 1,
                'smoothScrollEasing' => $options['smooth_scroll_easing'] ?? 'power2.out'
            ]);
        }
    }

    public function render_scroll_top() {
        $options = get_option('nexlifyscroll_options', []);
        if (!empty($options['scroll_top_enabled'])) {
            include NEXLIFYSCROLL_PATH . 'includes/frontend/templates/scroll-top.php';
        }
    }
}
?>