<?php
/**
 * WHWAS class
 *
 * @author  WPHobby
 * @package WooCommerce Ajax Search
 * @version 1.0.0
 */
class WHWAS {

    public $options;

    /**
     * @var bool Check WooCommerce Version
     * @since 1.0.0
     */
    public $current_wc_version  = false;
    public $is_wc_older_2_1     = false;
    public $is_wc_older_2_6     = false;

    public function __construct() {
        $this->options = get_option(WHWAS_OPTIONS);

        /**
         * WooCommerce Version Check
         */
        $this->current_wc_version = WC()->version;
        $this->is_wc_older_2_1    = version_compare( $this->current_wc_version, '2.1', '<' );
        $this->is_wc_older_2_6    = version_compare( $this->current_wc_version, '2.6', '<' );

        /* Add Search Form ShortCode */
        add_shortcode( 'wphobby_woocommerce_ajax_search', array( $this, 'element' ) );


        /* Enqueue Style and Scripts */
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );

    }

    public function activate(){
        //plugin default opts
        $init_opts = array(
            'version' => WHWAS_VERSION
        );

        if(!empty($this->options)){
            // update existed options
            update_option(WHWAS_OPTIONS, $init_opts);
        }else{
            // add the init options
            add_option(WHWAS_OPTIONS, $init_opts);
        }
    }

    public function initialize(){
    }

    public function deactivate(){
    }

    /**
     * Enqueue Styles and Scripts
     */
    public function enqueue_styles_scripts() {
        wp_enqueue_style('font-awesome', WHWAS_URL . 'assets/css/font-awesome.min.css', false, WHWAS_VERSION );
        wp_enqueue_style('flaticon', WHWAS_URL . 'assets/css/flaticon.css', false, WHWAS_VERSION );
        wp_enqueue_style( 'whwas-frontend-style', WHWAS_URL . 'assets/css/frontend.css', false, WHWAS_VERSION );
        wp_enqueue_script( 'whwas-frontend-script', WHWAS_URL . 'assets/js/frontend.js', array( 'jquery' ), WHWAS_VERSION, true );

        wp_localize_script('whwas-frontend-script', 'whwas_vars', array(
            'sale'       => __('Sale!', 'wphobby-woo-ajax-search'),
            'sku'        => __('SKU', 'wphobby-woo-ajax-search'),
            'showmore'   => __('View all results', 'wphobby-woo-ajax-search'),
            'noresults'  => __('Nothing found', 'wphobby-woo-ajax-search'),
        ));
    }


    /*
	 * Generate search box element
	 */
    public function element( $args = array() ) {

        $element = new WHWAS_Element();

        return $element->output();

    }
}
?>