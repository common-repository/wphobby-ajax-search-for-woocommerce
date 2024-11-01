<?php
/**
 * WHWAS Admin class
 *
 * @author  WPHobby
 * @package WooCommerce Ajax Search
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'WHWAS_Element' ) ) :

    /**
     * Class for plugin search action
     */
    class WHWAS_Element {

        /*
         * Generate search box markup
         */
        public function output() {

            $general_options = get_option( 'whwas_general_data' );


            $placeholder   = $general_options['whwas_field_search_text'];
            $min_chars     = $general_options['whwas_field_min_chars'];
            $show_loader   = $general_options['whwas_field_show_loader'];
            $show_more     = $general_options['whwas_field_show_more'];
            $show_page     = 'false';
            $show_clear    = $general_options['whwas_field_show_clear'];
            $use_analytics = 'false';
            $buttons_order = '1';

            $current_lang = '';

            $url_array = parse_url( home_url() );
            $url_query_parts = array();

            if ( isset( $url_array['query'] ) && $url_array['query'] ) {
                parse_str( $url_array['query'], $url_query_parts );
            }

            $form_action = home_url( '/' );
            if ( function_exists( 'pll_home_url' ) ) {
                $form_action = pll_home_url();
            }

            $params_string = '';

            $params = array(
                'data-url'           => class_exists( 'WC_AJAX' ) ? WC_AJAX::get_endpoint( 'whwas_action' ) : admin_url( 'admin-ajax.php' ),
                'data-siteurl'       => home_url(),
                'data-lang'          => $current_lang ? $current_lang : '',
                'data-show-loader'   => $show_loader,
                'data-show-more'     => $show_more,
                'data-show-page'     => $show_page,
                'data-show-clear'    => $show_clear,
                'data-use-analytics' => $use_analytics,
                'data-min-chars'     => $min_chars,
                'data-buttons-order' => $buttons_order,
                'data-is-mobile'     => wp_is_mobile() ? 'true' : 'false',
                'data-page-id'       => get_queried_object_id(),
                'data-tax'           => get_query_var('taxonomy')
            );


            /**
             * Filter form data parameters before output
             * @since 1.69
             * @param array $params Data parameters array
             */
            $params = apply_filters( 'whwas_front_data_parameters', $params );


            foreach( $params as $key => $value ) {
                $params_string .= $key . '="' . esc_attr( $value ) . '" ';
            }

            $element = '';
            $element .= '<div class="whwas-container" ' . $params_string . '>';
            $element .= '<form class="whwas-search-form" action="' . $form_action . '" method="get" role="search" >';

            $element .= '<div class="whwas-wrapper">';

            $element .= '<input  type="search" name="s" value="' . get_search_query() . '" class="whwas-search-field" placeholder="' . esc_attr( $placeholder ) . '" autocomplete="off" />';
            $element .= '<input type="hidden" name="post_type" value="product">';
            $element .= '<input type="hidden" name="type_whwas" value="true">';

            if ( $current_lang ) {
                $element .= '<input type="hidden" name="lang" value="' . esc_attr( $current_lang ) . '">';
            }

            if ( $url_query_parts ) {
                foreach( $url_query_parts as $url_query_key => $url_query_value  ) {
                    $element .= '<input type="hidden" name="' . esc_attr( $url_query_key ) . '" value="' . esc_attr( $url_query_value ) . '">';
                }
            }

            $element .= '<div class="whwas-search-clear">';
            $element .= '<span aria-label="Clear Search">×</span>';
            $element .= '</div>';

            $element .= '<div class="whwas-loader"></div>';

            $element .= '</div>';

            if ( $buttons_order && $buttons_order !== '1' ) {

                $element .= '<div class="whwas-search-btn whwas-form-btn">';
                $element .= '<span class="whwas-search-btn_icon">';
                $element .= '<svg focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24px">';
                $element .= '<path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>';
                $element .= '</svg>';
                $element .= '</span>';
                $element .= '</div>';

            }

            $element .= '</form>';
            $element .= '</div>';

            return apply_filters( 'whwas_search_box_element', $element );

        }

    }

endif;