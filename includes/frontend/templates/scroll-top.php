<?php
$options = get_option('nexlifyscroll_options', []);
$position = $options['button_position'] ?? 'bottom-right';
$position_styles = [
    'bottom-right' => 'bottom: 20px; right: 20px;',
    'bottom-left' => 'bottom: 20px; left: 20px;',
    'bottom-center' => 'bottom: 20px; left: 50%; transform: translateX(-50%);'
];
?>
<button id="nexlifyscroll-top" style="background-color: <?php echo esc_attr($options['button_color'] ?? '#000000'); ?>; <?php echo esc_attr($position_styles[$position] ?? $position_styles['bottom-right']); ?>" aria-label="<?php _e('Scroll to Top', 'nexlifyscroll'); ?>">
    <img src="<?php echo esc_url(!empty($options['button_icon']) ? wp_get_attachment_url($options['button_icon']) : NEXLIFYSCROLL_URL . 'assets/images/default-icon.png'); ?>" alt="<?php _e('Scroll to Top', 'nexlifyscroll'); ?>">
</button>
<?php
?>