<?php
/**
 * WHobby WooCommerce Product Filter Panel
 */
?>
<h2 class="nav-tab-wrapper">
    <?php $url = admin_url().'admin.php?page=whwas-help' ?>
    <a href="<?php echo esc_url($url); ?>" class="nav-tab <?php echo ($_GET[ 'page' ] == 'whwas-panel' && !isset($_GET[ 'tab' ]) )? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Help & Guide', 'whwas-admin' ); ?></a>
    <a href="<?php echo esc_url($url.'&tab=change-log'); ?>" class="nav-tab <?php echo $_GET[ 'tab' ] == 'change-log' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Change Log', 'whwas-admin' ); ?></a>
</h2>