<?php
/**
 * Pulls all the event data.
 */

require_once('../../../../wp-config.php');

$args = array("post_type" => "views_events", "posts_per_page"=> -1);
$event_query = new WP_Query($args);
$event_array = array();

if ($event_query->have_posts()) {

    while ($event_query->have_posts()) {
        $event_query->the_post();

        /***************************************
         * START DATE AND TIME
         ***************************************/
        //date
        $month = date("m", get_post_meta(get_the_ID(), "views_event_date", true));
        $day = date("d", get_post_meta(get_the_ID(), "views_event_date", true));
        $year = date("Y", get_post_meta(get_the_ID(), "views_event_date", true));
        //time
        $hours = date("H", get_post_meta(get_the_ID(), "views_event_time", true));
        $minutes = date("i", get_post_meta(get_the_ID(), "views_event_time", true));
        //set event object
        $eventDateTime = new DateTime();
        $eventDateTime->setDate($year, $month, $day);
        $eventDateTime->setTime($hours, $minutes);
        $event_date = $eventDateTime->format(DateTime::ATOM);//convert to ATOM
        /******************************************
         * END DATE AND TIME
         ******************************************/
        //date
        $end_month = date("m", get_post_meta(get_the_ID(), "views_event_end_date", true));
        $end_day = date("d", get_post_meta(get_the_ID(), "views_event_end_date", true));
        $end_year = date("Y", get_post_meta(get_the_ID(), "views_event_end_date", true));
        //time
        $end_hours = date("H", get_post_meta(get_the_ID(), "views_event_end_time", true));
        $end_minutes = date("i", get_post_meta(get_the_ID(), "views_event_end_time", true));
        //set event object
        $eventDateEndTime = new DateTime();
        $eventDateEndTime->setDate($end_year, $end_month, $end_day);
        $eventDateEndTime->setTime($end_hours, $end_minutes);
        $event_end_date = $eventDateEndTime->format(DateTime::ATOM);//convert to ATOM

        //set color vars
        $event_color = get_field('event_color', get_the_ID());//get color
        //set array
        $event_array[] = array("id" => get_the_ID(), "title" => get_the_title(), "start" => $event_date, "end" => $event_end_date, "color"=>$event_color);
    }
}

echo json_encode($event_array);

?>


