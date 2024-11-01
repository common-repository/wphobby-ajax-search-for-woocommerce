<?php
/**
 * General Settings template
 */

?>
<div id="tab-activate" class="panel whwas-panel">
    <div class="panel-wrapper">
        <h3>General Settings</h3>
        <form id="whwas-panel" method="post" action="options.php">
            <?php
            settings_fields( 'whwas_general' );
            do_settings_sections( 'whwas_panel_general' );
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
</div>
