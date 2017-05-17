/**
 * Created by Greg on 2/4/2017.
 */


$(function () {
    initEvents();
});

/*************************************
 * 01. INITIALIZE EVENTS
 * Fetches Events data from database.
 *************************************/
function initEvents(){
    var ajaxPath = $("#ajaxPath").val();
    $.ajax({
        url: ajaxPath + "/eventsJson.php",
        type:"POST",
        dataType:"json",
        success: function(response){
            createCalendar(response);
        }
    });
}
/******************************************************
 * 02. CREATE CALENDAR
 * Creates calendar and loades the events.
 * @param events    - json result for events posttype
 ******************************************************/
function createCalendar(events) {
    $("#calendar").fullCalendar({'events': events});
}

