<?php
?>
<div class="wrap">
    <h1><?php _e('NexlifyScroll Settings', 'nexlifyscroll'); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('nexlifyscroll_options_group');
        do_settings_sections('nexlifyscroll_scroll_top');
        submit_button();
        ?>
    </form>
</div>
<?php
?>