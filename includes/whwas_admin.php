<?php
/**
 * WHWAS Admin class
 *
 * @author  WPHobby
 * @package WooCommerce Ajax Search
 * @version 1.0.0
 */
if( ! class_exists( 'WHWAS_Admin' ) ) {
    class WHWAS_Admin {
        // =============================================================================
        // Construct
        // =============================================================================
        public function __construct() {
            add_action( 'admin_init', array( $this, 'whwas_register_settings' ) );
            add_action( 'admin_menu', array( $this, 'whwas_register_menu' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'whwas_admin_styles_scripts' ) );
        }

        /**
         * Load welcome admin css and js
         * @return void
         * @since  1.0.0
         */
        public function whwas_admin_styles_scripts() {
            if ( is_admin() ) {
                wp_enqueue_style('font-awesome', WHWAS_URL . 'assets/css/font-awesome.min.css', false, WHWAS_VERSION );
                wp_enqueue_style( 'whwas-admin-style', WHWAS_URL . 'assets/css/admin.css', false, WHWAS_VERSION );

                if( isset( $_GET[ 'tab' ] ) && $_GET[ 'tab' ] == 'custom-style' ){
                    wp_enqueue_script( 'whwas-admin-script', WHWAS_URL . 'assets/js/admin.js', array( 'jquery' ), WHWAS_VERSION, true );

                    /* Add CodeMirror */
                    $cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/css'));
                    wp_localize_script('jquery', 'cm_settings', $cm_settings);

                    wp_enqueue_script('wp-theme-plugin-editor');
                    wp_enqueue_style('wp-codemirror');
                }

            }
        }

        /*
         * Display admin messages
         */
        public function whwas_display_message(){
            if ( isset( $_GET['settings-updated'] ) ) {
                echo "<div class='updated'><p>".__( 'Settings updated successfully.', 'wphobby-woo-ajax-search' )."</p></div>";
            }
        }

        /**
         * Register admin menus
         * @return void
         * @since  1.0.0
         */
        public function whwas_register_menu(){
            add_menu_page( 'WooCommerce Ajax Search', 'Woo Search', 'manage_options', 'whwas-panel', array( $this, 'whwas_panel_general' ), WHWAS_URL . '/assets/images/icon.svg', '2');
            add_submenu_page('whwas-panel', 'Help & Guide', 'Help & Guide', 'manage_options', 'whwas-help', array( $this, 'whwas_panel_help' ) );
        }

        /**
         * The admin panel content
         * @since 1.0.0
         */
        public function whwas_panel_general() {
            $active_tab = isset( $_GET[ 'tab' ] ) ? sanitize_text_field($_GET[ 'tab' ]) : 'general';
            ?>
            <div class="whwas-panel">
                <div class="wrap">
                    <?php require_once( WHWAS_DIR . '/includes/admin/sections/general/top.php' ); ?>
                    <?php $this->whwas_display_message(); ?>
                    <?php
                    if( $active_tab == 'general' ){
                        require_once( WHWAS_DIR . '/includes/admin/sections/general/tab-general.php' );
                    }
                    else if($active_tab == 'advanced'){
                        require_once( WHWAS_DIR . '/includes/admin/sections/general/tab-advanced.php' );
                    }else if($active_tab == 'server'){
                        require_once( WHWAS_DIR . '/includes/admin/sections/general/tab-server.php' );
                    }
                    ?>
                </div>
            </div>
            <?php
        }

        /**
         * The admin panel help
         * @since 1.0.0
         */
        public function whwas_panel_help() {
            $active_tab = isset( $_GET[ 'tab' ] ) ? sanitize_text_field($_GET[ 'tab' ]) : 'help';
            ?>
            <div class="whwas-panel">
                <div class="wrap">
                    <?php require_once( WHWAS_DIR . '/includes/admin/sections/help/top.php' ); ?>
                    <?php $this->whwas_display_message(); ?>
                    <?php
                    if( $active_tab == 'help' ){
                        require_once( WHWAS_DIR . '/includes/admin/sections/help/tab-help.php' );
                    }else if($active_tab == 'change-log'){
                        require_once( WHWAS_DIR . '/includes/admin/sections/help/tab-change-log.php' );
                    }
                    ?>
                </div>
            </div>
            <?php
        }

        /**
         * Register Settings
         * @since 1.0.0
         */
        public function whwas_register_settings() {
            register_setting(
                'whwas_advanced', // A settings group name. Must exist prior to the register_setting call. This must match the group name in settings_fields()
                'whwas_advanced_data' //The name of an option to sanitize and save.
            );

            register_setting(
                'whwas_general', // A settings group name. Must exist prior to the register_setting call. This must match the group name in settings_fields()
                'whwas_general_data'
            );

            register_setting(
                'whwas_custom', // A settings group name. Must exist prior to the register_setting call. This must match the group name in settings_fields()
                'whwas_custom_data'
            );

            add_settings_section( 'whwas_section_general', '', array( $this, 'whwas_section_general_output' ), 'whwas_panel_general' );
            add_settings_field( 'whwas_field_search_text', esc_html__("Search Input Placeholder", "wphobby-woo-ajax-search"), array( $this, 'whwas_search_text_output' ), 'whwas_panel_general', 'whwas_section_general' );
            add_settings_field( 'whwas_field_min_chars', esc_html__("Minimum Characters", "wphobby-woo-ajax-search"), array( $this, 'whwas_min_chars_output' ), 'whwas_panel_general', 'whwas_section_general' );
            add_settings_field( 'whwas_field_show_loader', esc_html__("Show Loader", "wphobby-woo-ajax-search"), array( $this, 'whwas_show_loader_output' ), 'whwas_panel_general', 'whwas_section_general' );
            add_settings_field( 'whwas_field_show_more', esc_html__("Show More", "wphobby-woo-ajax-search"), array( $this, 'whwas_show_more_output' ), 'whwas_panel_general', 'whwas_section_general' );
            add_settings_field( 'whwas_field_show_clear', esc_html__("Show Clear", "wphobby-woo-ajax-search"), array( $this, 'whwas_show_clear_output' ), 'whwas_panel_general', 'whwas_section_general' );



            add_settings_section( 'whwas_section_advanced', '', array( $this, 'whwas_section_advanced_output' ), 'whwas_panel_advanced' );
            add_settings_field( 'whwas_field_max_results', esc_html__("Max number of results", "wphobby-woo-ajax-search"), array( $this, 'whwas_max_results_output' ), 'whwas_panel_advanced', 'whwas_section_advanced' );
            add_settings_field( 'whwas_field_show_image', esc_html__("Show Image", "wphobby-woo-ajax-search"), array( $this, 'whwas_show_image_output' ), 'whwas_panel_advanced', 'whwas_section_advanced' );
            add_settings_field( 'whwas_field_show_price', esc_html__("Show Price", "wphobby-woo-ajax-search"), array( $this, 'whwas_show_price_output' ), 'whwas_panel_advanced', 'whwas_section_advanced' );
            add_settings_field( 'whwas_field_show_sku', esc_html__("Show Product SKU", "wphobby-woo-ajax-search"), array( $this, 'whwas_show_sku_output' ), 'whwas_panel_advanced', 'whwas_section_advanced' );




        }

        public function whwas_section_general_output() {
            echo esc_html__( 'General display settings for WooCommerce Ajax Search.', 'wphobby-woo-ajax-search' );
        }

        public function whwas_search_text_output() {
            $options = get_option( 'whwas_general_data' );
            ?>
            <div class="tooltip">
                <i class="fa fa-question-circle"></i>
                <span class="tooltiptext">Your Server Input Placeholder</span>
            </div>
            <input type="text" name="whwas_general_data[whwas_field_search_text]" value='<?php echo isset($options['whwas_field_search_text']) ? esc_attr($options['whwas_field_search_text']) : get_bloginfo( 'name' ); ?>'/>
            <?php
        }

        public function whwas_min_chars_output() {
            $options = get_option( 'whwas_general_data' );
            ?>
            <div class="tooltip">
                <i class="fa fa-question-circle"></i>
                <span class="tooltiptext">Minimum number of characters required to trigger autosuggest.</span>
            </div>
            <input type="text" name="whwas_general_data[whwas_field_min_chars]" value='<?php echo isset($options['whwas_field_min_chars']) ? esc_attr($options['whwas_field_min_chars']) : 3; ?>'/>
            <?php
        }

        public function whwas_show_loader_output() {
            $options = get_option( 'whwas_general_data' );
            $value = 'true';
            $checked = isset($options['whwas_field_show_loader']) && $options['whwas_field_show_loader']== 'true' ? 'checked' : '';
            ?>
            <div class="tooltip">
                <i class="fa fa-question-circle"></i>
                <span class="tooltiptext">Show Loader when user search.</span>
            </div>
            <label class="switch">
                <input type="checkbox" value='<?php echo esc_attr($value); ?>' name='whwas_general_data[whwas_field_show_loader]' <?php echo esc_attr($checked); ?> />
                <span class="slider round"></span>
            </label>
            <?php
        }

        public function whwas_show_more_output() {
        $options = get_option( 'whwas_general_data' );
        $value = 'true';
        $checked = isset($options['whwas_field_show_more']) && $options['whwas_field_show_more']== 'true' ? 'checked' : '';
        ?>
        <div class="tooltip">
            <i class="fa fa-question-circle"></i>
            <span class="tooltiptext">Show More when user search.</span>
        </div>
        <label class="switch">
            <input type="checkbox" value='<?php echo esc_attr($value); ?>' name='whwas_general_data[whwas_field_show_more]' <?php echo esc_attr($checked); ?> />
            <span class="slider round"></span>
        </label>
        <?php
    }

        public function whwas_show_clear_output() {
            $options = get_option( 'whwas_general_data' );
            $value = 'true';
            $checked = isset($options['whwas_field_show_clear']) && $options['whwas_field_show_clear']== 'true' ? 'checked' : '';
            ?>
            <div class="tooltip">
                <i class="fa fa-question-circle"></i>
                <span class="tooltiptext">Show Clear when user search.</span>
            </div>
            <label class="switch">
                <input type="checkbox" value='<?php echo esc_attr($value); ?>' name='whwas_general_data[whwas_field_show_clear]' <?php echo esc_attr($checked); ?> />
                <span class="slider round"></span>
            </label>
            <?php
        }


        public function whwas_section_advanced_output() {
            echo esc_html__( 'Advanced Settings for Search Result.', 'wphobby-woo-ajax-search' );
        }

        public function whwas_show_image_output() {
            $options = get_option( 'whwas_advanced_data' );
            $value = 'true';
            $checked = isset($options['whwas_field_show_image']) && $options['whwas_field_show_image']== 'true' ? 'checked' : '';
            ?>
            <div class="tooltip">
                <i class="fa fa-question-circle"></i>
                <span class="tooltiptext">Show Image on search result.</span>
            </div>
            <label class="switch">
                <input type="checkbox" value='<?php echo esc_attr($value); ?>' name='whwas_advanced_data[whwas_field_show_image]' <?php echo esc_attr($checked); ?> />
                <span class="slider round"></span>
            </label>
            <?php
        }

        public function whwas_show_price_output() {
            $options = get_option( 'whwas_advanced_data' );
            $value = 'true';
            $checked = isset($options['whwas_field_show_price']) && $options['whwas_field_show_price']== 'true' ? 'checked' : '';
            ?>
            <div class="tooltip">
                <i class="fa fa-question-circle"></i>
                <span class="tooltiptext">Show Price on search result.</span>
            </div>
            <label class="switch">
                <input type="checkbox" value='<?php echo esc_attr($value); ?>' name='whwas_advanced_data[whwas_field_show_price]' <?php echo esc_attr($checked); ?> />
                <span class="slider round"></span>
            </label>
            <?php
        }

        public function whwas_show_sku_output() {
            $options = get_option( 'whwas_advanced_data' );
            $value = 'true';
            $checked = isset($options['whwas_field_show_sku']) && $options['whwas_field_show_sku']== 'true' ? 'checked' : '';
            ?>
            <div class="tooltip">
                <i class="fa fa-question-circle"></i>
                <span class="tooltiptext">Show Product SKU on search result.</span>
            </div>
            <label class="switch">
                <input type="checkbox" value='<?php echo esc_attr($value); ?>' name='whwas_advanced_data[whwas_field_show_sku]' <?php echo esc_attr($checked); ?> />
                <span class="slider round"></span>
            </label>
            <?php
        }

        public function whwas_max_results_output() {
            $options = get_option( 'whwas_advanced_data' );
            ?>
            <div class="tooltip">
                <i class="fa fa-question-circle"></i>
                <span class="tooltiptext">Max number of results to show.</span>
            </div>
            <input type="text" name="whwas_advanced_data[whwas_field_max_results]" value='<?php echo isset($options['whwas_field_max_results']) ? esc_attr($options['whwas_field_max_results']) : 3; ?>'/>
            <?php
        }
    }

    new WHWAS_Admin;
}