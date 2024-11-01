<?php
/**
 * Custom Style template
 */

?>
<div id="tab-activate" class="panel whwas-panel">
    <div class="panel-wrapper">
        <h3>Selectors Settings</h3>
        <form id="whwas-panel" method="post" action="options.php">
            <?php
            settings_fields( 'whwas_custom' );
            do_settings_sections( 'whwas_panel_custom_style' );
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
</div>
