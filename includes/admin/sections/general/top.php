<?php
/**
 * WHobby WooCommerce Product Filter Panel
 */
?>
<h2 class="nav-tab-wrapper">
    <?php $url = admin_url().'admin.php?page=whwas-panel' ?>
    <a href="<?php echo esc_url($url); ?>" class="nav-tab <?php echo ($_GET[ 'page' ] == 'whwas-panel' && !isset($_GET[ 'tab' ]) )? 'nav-tab-active' : ''; ?>"><?php esc_html_e('General', 'whwas-admin' ); ?></a>
    <a href="<?php echo esc_url($url.'&tab=advanced'); ?>" class="nav-tab <?php echo $_GET[ 'tab' ] == 'advanced' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Advanced', 'whwas-admin' ); ?></a>
    <a href="<?php echo esc_url($url.'&tab=server'); ?>" class="nav-tab <?php echo $_GET[ 'tab' ] == 'server' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Server Info', 'whwas-admin' ); ?></a>
</h2>