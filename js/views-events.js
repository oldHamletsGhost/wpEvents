/***********************************************
 * Created by Greg on 2/4/2017.
 * Functions for admin side of View's events.
 *
 * TABLE OF CONTENTS
 * ----------------------------------
 * XX. ASSIGN FUNCTIONS
 * 01. CAPTURE ADDRESS FIELDS
 * 02. GET LOCATION
 ************************************************/
jQuery(document).ready(function($){

    /***********************
     * XX. Assign functions
     ***********************/
    $("#event-date").datepicker({dateFormat: 'M d, yy'});//uses jqueryUI to set date field
    $("#event-end-date").datepicker({dateFormat: 'M d, yy'});//uses jqueryUI to set date field
    $("#event-time").timepicker();//uses jquery.timepicker.min.js to set the time field
    $("#event-end-time").timepicker();//uses jquery.timepicker.min.js to set the time field

    $("#calc-coords").on("click", captureAddressFields);

    //gets the current value of the event date && sets datepicker if it exists
    var eventDateDB = $("input#event-date-db").val();
    var eventEndDateDB = $("input#event-end-date-db").val();
    if(eventDateDB != null && eventDateDB != ''){
        $("#event-date").datepicker("setDate", $.datepicker.formatDate('M d, yy', new Date(eventDateDB)));
    }
    if(eventEndDateDB != null && eventEndDateDB != ''){
        $("#event-end-date").datepicker("setDate", $.datepicker.formatDate('M d, yy', new Date(eventEndDateDB)));
    }
});

/*********************************************
 * 01. CAPTURE ADDRESS FIELDS
 * Accesses the address fields from the post.
 *********************************************/
function captureAddressFields(e){
    e.preventDefault();
    var address = jQuery("#event-address").val();
    var city = jQuery("#event-city").val();
    var prov = jQuery("#event-prov-or-state").val();
    var postalCode = jQuery("#event-postal-code").val();
    var country = jQuery("#event-country").val();
    var concatAddress = address+city+prov+postalCode+country;
    getLocation(concatAddress);
}

/**********************************************************************
 * 02. GET LOCATION
 * Gets the latitude and longitude of the address and stores it in the
 * latitude and longitude input fields.
 * @param address   - concatenated address value
 **********************************************************************/
function getLocation(address) {
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'address': address }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var latitude = results[0].geometry.location.lat();
            var longitude = results[0].geometry.location.lng();
            jQuery("#event-latitude").val(latitude);
            jQuery("#event-longitude").val(longitude);
        } else {
            alert("The latitude and longitude could not be determined.")
        }
    });
}