<?php
class NexlifyScroll_Activation {
    public function __construct() {
        register_activation_hook(NEXLIFYSCROLL_PATH . 'nexlifyscroll.php', [$this, 'activate']);
    }

    public function activate() {
        $defaults = [
            'scroll_top_enabled' => true,
            'button_color' => '#000000',
            'button_icon' => '',
            'button_position' => 'bottom-right',
            'easing' => 'power2.out',
            'duration' => 0.8
        ];
        if (!get_option('nexlifyscroll_options')) {
            update_option('nexlifyscroll_options', $defaults);
        }
    }
}
?>