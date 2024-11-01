<?php
/*
 * Initialized plugins widget
 */

add_action( 'widgets_init', 'whwas_register_widget' );
 
function whwas_register_widget() {
    register_widget("WHWAS_Widget");
}

class WHWAS_Widget extends WP_Widget {

    /*
     * Constructor
     */
    function __construct() {
        $widget_ops = array( 'description' => __('WooCommerce Ajax Search Widget', 'wphobby-woo-ajax-search' ) );
        $control_ops = array( 'width' => 400 );
        parent::__construct( false, __( 'Woo Search', 'wphobby-woo-ajax-search' ), $widget_ops, $control_ops );
    }

    /*
     * Display widget
     */
    function widget( $args, $instance ) {
        extract( $args );

        $title = apply_filters( 'widget_title',
            ( ! empty( $instance['title'] ) ? $instance['title'] : '' ),
            $instance,
            $this->id_base
        );

        echo $before_widget;
        echo $before_title;
        echo $title;
        echo $after_title;

        // Generate search form html
        $WHWAS = new WHWAS();
        echo $WHWAS->element();

        echo $after_widget;
    }

    /*
     * Update widget settings
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $params = array( 'title' );
        foreach ( $params as $k ) {
            $instance[$k] = strip_tags( $new_instance[$k] );
        }
        return $instance;
    }

    /*
     * Widget settings form
     */
    function form( $instance ) {
        global $shortname;
        $defaults = array(
            'title' => __( 'Search...', 'wphobby-woo-ajax-search' )
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e( 'Title:', 'wphobby-woo-ajax-search' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>">
        </p>

    <?php
    }
}
?>