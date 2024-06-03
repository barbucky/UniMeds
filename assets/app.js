
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */


$(document).ready(function () {
    $('#appointment_date_of_appointment').change(function () {
        updateAppointments();
        
    })
});

function updateAppointments() {
    let SelectDate = $('#appointment_date_of_appointment').val();
    
}
