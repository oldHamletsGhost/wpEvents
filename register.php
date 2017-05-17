<?php
/*
Plugin Name: Viewson's Events Plugin
Description: Custom plugin built to handle the events for Viewson.ca.
Author: Greg Goldsberry
Version: 1.0
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class voEvents
{

    const PLUGIN_FOLDER = 'viewson-events';

    /************************
     * voEvents constructor.
     ************************/
    function __construct()
    {

        add_action('init', array(&$this, 'init'));

        if (is_admin()) {

            add_action('admin_init', array(&$this, 'admin_init'));

        }
    }

    /****************************
     *  Initialize Post Type
     ****************************/
    function init()
    {

        register_post_type('views_events',
            array(
                'labels' => array(
                    'name' => __('Events'),
                    'singular_name' => __('Event'),
                    'add_new' => 'Add New Event',
                    'add_new_item' => 'Add New Event',
                    'edit_item' => 'Edit Listing',
                    'view_item' => 'View Listing'

                ),

                'public' => true,
                'has_archive' => false,
                'show_in_nav_menus' => true,
                'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
                'menu_icon' => 'dashicons-calendar-alt',
                'publicly_queryable' => true,
//                'taxonomies' => array("category")

            )
        );

    }

    /***********************************************************
     *  Intialize meta boxes, save function and scripts/styles
     ***********************************************************/
    function admin_init()
    {

        // add meta boxes
        add_action('add_meta_boxes', array(&$this, 'add_events_metaboxes'));

        // save meta
        add_action('save_post', array(&$this, 'save_event'), 1, 2);

        //add scripts
        add_action('admin_enqueue_scripts', array(&$this, 'add_scripts'));

    }

    /************************************
     *  Load styles sheets and scripts
     ************************************/
    function add_scripts()
    {

        $googleApiKey = get_option("vo_google_api_key");
        //styles
        wp_enqueue_style('jqueryUI-style', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
        wp_enqueue_style('timepicker', plugins_url() . "/viewson-events/css/jquery.timepicker.css");
        wp_enqueue_style('custom-style', plugins_url() . "/viewson-events/css/admin-events.css");
        //scripts TODO: change API key on launch
        wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key='.$googleApiKey.'&sensor=false');
        wp_enqueue_script('google-jsapi', 'https://www.google.com/jsapi');
        wp_enqueue_script('jqueryUI', "http://code.jquery.com/ui/1.12.1/jquery-ui.min.js");
        wp_enqueue_script('jqueryTimePicker', plugins_url() . "/viewson-events/js/jquery.timepicker.min.js", array('jquery'));
        wp_enqueue_script('views-events', plugins_url() . "/viewson-events/js/views-events.js", array('jquery'));
    }

    /**********************
     *  Add Meta boxes
     **********************/
    function add_events_metaboxes()
    {
        add_meta_box('events_data', 'Event Information', array(&$this, 'events_data'), 'views_events', 'normal', 'high');
    }

    /*****************************************
     * Creates markup and pulls saved values
     * @param $post     - post object
     *****************************************/
    function events_data($post)
    {

        $date = get_post_meta($post->ID, 'views_event_date', true);
        $time = get_post_meta($post->ID, 'views_event_time', true);
        $end_date = get_post_meta($post->ID, 'views_event_end_date', true);
        $end_time = get_post_meta($post->ID, 'views_event_end_time', true);



        $event_address = get_post_meta($post->ID, 'views_event_address', true);
        $event_date = isset($date) && $date != "" ? date("M d, Y" , $date) : "";
        $event_time = isset($time) && $time != "" ? date("H:i A", $time) : "";

        $event_end_date = isset($end_date) && $end_date != "" ? date("M d, Y" , $end_date) : "";
        $event_end_time = isset($end_time) && $end_time != "" ? date("H:i A", $end_time) : "";



        $event_city = get_post_meta($post->ID, 'views_event_city', true);
        $event_prov_or_state = get_post_meta($post->ID, 'views_event_prov_or_state', true);
        $event_country = get_post_meta($post->ID, 'views_event_country', true);
        $event_postal_code = get_post_meta($post->ID, 'views_event_postal_code', true);
        $event_latitude = get_post_meta($post->ID, 'views_event_latitude', true);
        $event_longitude = get_post_meta($post->ID, 'views_event_longitude', true);
        ?>

        <div class="events-data">
            <div class="form-table">

                <div class="row">
                    <div class="form-container">
                        <label for="event-date">Start Date</label>
                        <input type="text" name="event-date" id="event-date">
                        <input type="hidden" id="event-date-db" value="<?php echo $event_date;?>">
                    </div>
                    <div class="form-container">
                        <label for="event-time">Start Time</label>
                        <input type="text" name="event-time" id="event-time" value="<?php echo $event_time; ?>">
                    </div>
                </div><!-- .row -->


                <div class="row">
                    <div class="form-container">
                        <label for="event-date">End Date</label>
                        <input type="text" name="event-end-date" id="event-end-date">
                        <input type="hidden" id="event-end-date-db" value="<?php echo $event_end_date;?>">
                    </div>
                    <div class="form-container">
                        <label for="event-end-time">End Time</label>
                        <input type="text" name="event-end-time" id="event-end-time" value="<?php echo $event_end_time; ?>">
                    </div>
                </div><!-- .row -->

                <div class="row">
                    <div class="form-container">
                        <label for="event-address">Address:</label>
                        <input type="text" name="event-address" id="event-address"
                               value="<?php echo $event_address; ?>">
                    </div>
                    <div class="form-container">
                        <label for="event-city">City:</label>
                        <input type="text" name="event-city" id="event-city" value="<?php echo $event_city; ?>">
                    </div>
                </div><!-- .row -->

                <div class="row">
                    <div class="form-container">
                        <label for="event-prov-or-state">Province or State:</label>
                        <input type="text" name="event-prov-or-state" id="event-prov-or-state"
                               value="<?php echo $event_prov_or_state; ?>">
                    </div>
                    <div class="form-container">
                        <label for="event-country">Country:</label>
                        <input type="text" name="event-country" id="event-country"
                               value="<?php echo $event_country; ?>">
                    </div>
                </div><!-- .row -->

                <div class="row last">
                    <div class="form-container">
                        <label for="event-postal-code">Postal Code</label>
                        <input type="text" name="event-postal-code" id="event-postal-code" value="<?php echo $event_postal_code ?>"></div>
                </div><!-- .row -->

                <div class="row">
                    <div class="form-container button-container">
                        <label for="event-generate-locale">Get Coordinates</label>
                        <button id="calc-coords">Calculate</button>
                    </div>
                </div><!-- .row -->

                <div class="row">
                    <div class="form-container">
                        <label for="event-latitude">Latitude:</label>
                        <input type="text" name="event-latitude" id="event-latitude"
                               value="<?php echo $event_latitude; ?>"
                               readonly>
                    </div><!-- .row -->

                    <div class="form-container">
                        <label for="event-longitude">Longitude:</label>
                        <input type="text" name="event-longitude" id="event-longitude"
                               value="<?php echo $event_longitude; ?>" readonly>
                    </div>
                </div><!-- .row -->

            </div><!-- .form-table -->
        </div><!-- .event-data -->
        <?php

    }

    /******************************
     * Saves custom entries
     *
     * @param $post_id
     * @param $post
     * @return int|void
     ******************************/
    function save_event($post_id, $post)
    {

        // Verify if this is an auto save routine.
        // If it is our form has not been submitted, so we dont want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post->ID)) {
            return $post->ID;
        }

        // if on product edit page
        if (get_post_type($post) != "views_events") {
            return $post->ID;
        }

        update_post_meta($post->ID, 'views_event_address', $_POST['event-address']);
        update_post_meta($post->ID, 'views_event_date', strtotime($_POST['event-date']));
        update_post_meta($post->ID, 'views_event_time', strtotime($_POST['event-time']));
        update_post_meta($post->ID, 'views_event_end_date', strtotime($_POST['event-end-date']));
        update_post_meta($post->ID, 'views_event_end_time', strtotime($_POST['event-end-time']));
        update_post_meta($post->ID, 'views_event_city', $_POST['event-city']);
        update_post_meta($post->ID, 'views_event_prov_or_state', $_POST['event-prov-or-state']);
        update_post_meta($post->ID, "views_event_postal_code", $_POST['event-postal-code']);
        update_post_meta($post->ID, 'views_event_country', $_POST['event-country']);
        update_post_meta($post->ID, 'views_event_latitude', $_POST['event-latitude']);
        update_post_meta($post->ID, 'views_event_longitude', $_POST['event-longitude']);
    }


}

$events = new voEvents();

?>
