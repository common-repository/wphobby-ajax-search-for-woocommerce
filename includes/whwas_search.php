<?php
/**
 * WHWAS Search class
 *
 * @author  WPHobby
 * @package WooCommerce Ajax Search
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'WHWAS_Search' ) ) :

    /**
     * Class for plugin search
     */
    class WHWAS_Search {

        /**
         * @var WHWAS_Search Array of all plugin data $data
         */
        private $data = array();

        /**
         * Return a singleton instance of the current class
         *
         * @return object
         */
        public static function factory() {
            static $instance = false;

            if ( ! $instance ) {
                $instance = new self();
                $instance->setup();
            }

            return $instance;
        }

        /**
         * Constructor
         */
        public function __construct() {}

        /**
         * Setup actions and filters for all things settings
         */
        public function setup() {

            $this->data['settings'] = get_option( 'whwas_settings' );

            if ( isset( $_REQUEST['wc-ajax'] ) ) {
                add_action( 'wc_ajax_whwas_action', array( $this, 'action_callback' ) );
            } else {
                add_action( 'wp_ajax_whwas_action', array( $this, 'action_callback' ) );
                add_action( 'wp_ajax_nopriv_whwas_action', array( $this, 'action_callback' ) );
            }

        }

        /*
         * AJAX call action callback
         */
        public function action_callback() {

            if ( ! defined( 'DOING_AJAX' ) ) {
                define( 'DOING_AJAX', true );
            }

            echo json_encode( $this->ajax_search_products() );

            die;

        }

        /**
         * Perform ajax search products
         */
        public function ajax_search_products() {

            $advanced_options = get_option( 'whwas_advanced_data' );

            $search_keyword = htmlspecialchars_decode( esc_attr( $_POST['keyword'] ) );

            $suggestions   = array();

            $args = array(
                's'                   => apply_filters( 'whwas_ajax_search_products_search_query', $search_keyword ),
                'post_type'           => 'product',
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page'      => apply_filters( 'whwas_ajax_search_products_posts_per_page', $advanced_options['whwas_field_max_results'] ),
                'suppress_filters'    => false,
            );

            if ( isset( $_REQUEST['product_cat'] ) ) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => sanitize_text_field($_REQUEST['product_cat'])
                    )
                );
            }

            if ( version_compare( WC()->version, '2.7.0', '<' ) ) {
                $args['meta_query'] = array(
                    array(
                        'key'     => '_visibility',
                        'value'   => array( 'search', 'visible' ),
                        'compare' => 'IN'
                    ),
                );
            }else{
                $product_visibility_term_ids = wc_get_product_visibility_term_ids();
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => $product_visibility_term_ids['exclude-from-search'],
                    'operator' => 'NOT IN',
                );
            }


            $products = get_posts( $args );

            if ( ! empty( $products ) ) {
                foreach ( $products as $post ) {
                    $product = wc_get_product( $post );

                    $image = '';
                    $price = '';
                    $sku   = '';

                    if ( $advanced_options['whwas_field_show_image'] === 'true' ) {
                        $image_id = $product->get_image_id();
                        $image_attributes = wp_get_attachment_image_src( $image_id );
                        $image = $image_attributes[0];
                    }

                    if ( $advanced_options['whwas_field_show_price'] === 'true' ) {
                        $price = wc_price($product->get_price());
                    }

                    if ( $advanced_options['whwas_field_show_sku'] === 'true' ) {
                        $sku = $product->get_sku();
                    }

                    $suggestions[] = apply_filters( 'whwas_suggestion', array(
                        'id'    => $product->get_id(),
                        'value' => strip_tags( $product->get_title() ),
                        'url'   => $product->get_permalink(),
                        'price' => $price,
                        'image' => $image,
                        'sku'   => $sku,
                    ), $product );

                }
            } else {
                $suggestions[] = array(
                    'id'    => - 1,
                    'value' => __( 'No results', 'wphobby-woocommerce-ajax-search' ),
                    'url'   => '',
                );
            }



            wp_reset_postdata();



            $suggestions = array(
                'products' => $suggestions,
            );

            return $suggestions;

        }

    }


endif;

WHWAS_Search::factory();