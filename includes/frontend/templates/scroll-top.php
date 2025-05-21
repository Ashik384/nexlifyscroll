<?php
$options = get_option('nexlifyscroll_options', []);
$position = $options['button_position'] ?? 'bottom-right';
$position_styles = [
    'bottom-right' => 'bottom: 20px; right: 20px;',
    'bottom-left' => 'bottom: 20px; left: 20px;'
];
$icon_url = !empty($options['button_icon']) ? wp_get_attachment_url($options['button_icon']) : NEXLIFYSCROLL_URL . 'assets/images/default-icon.png';
?>
<button id="nexlifyscroll-top" style="background-color: <?php echo esc_attr($options['button_color'] ?? '#000000'); ?>; <?php echo esc_attr($position_styles[$position] ?? $position_styles['bottom-right']); ?>; --progress-color: <?php echo esc_attr($options['progress_bar_color'] ?? '#ff0000'); ?>" aria-label="<?php _e('Scroll to Top', 'nexlifyscroll'); ?>">
    <div class="nexlifyscroll-icon">
        <img src="<?php echo esc_url($icon_url); ?>" alt="<?php _e('Scroll to Top', 'nexlifyscroll'); ?>">
    </div>
</button>