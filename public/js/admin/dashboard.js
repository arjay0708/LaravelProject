$(document).ready(function(){
    totalPendingReservation();
    totalOnGoingReservation();
    totalCompletedReservation();
    totalCustomer();
});


// FUNCTION FOR SHOW TOTAL UPCOMING OPERATION
    function totalPendingReservation(){
        $.ajax({
            url: '/totalPendingReservation',
            method: 'GET',
            success : function(data) {
                $("#totalPendingReservation").html(data);
            }
        })
    }
// FUNCTION FOR SHOW TOTAL UPCOMING OPERATION

// FUNCTION FOR SHOW TOTAL COMPLETED OPERATION
    function totalOnGoingReservation(){
        $.ajax({
            url: '/totalOnGoingReservation',
            method: 'GET',
            success : function(data) {
                $("#totalOnGoingReservation").html(data);
            }
        })
    }
// FUNCTION FOR SHOW TOTAL COMPLETED OPERATION

// FUNCTION FOR SHOW TOTAL APPLICANTS
    function totalCompletedReservation(){
        $.ajax({
            url: '/totalCompletedReservation',
            method: 'GET',
            success : function(data) {
                $("#totalCompletedReservation").html(data);
            }
        })
    }
// FUNCTION FOR SHOW TOTAL APPLICANTS

// FUNCTION FOR SHOW TOTAL FOREMAN
    function totalCustomer(){
        $.ajax({
            url: '/totalCustomer',
            method: 'GET',
            success : function(data) {
                $("#totalCustomer").html(data);
            }
        })
    }
// FUNCTION FOR SHOW TOTAL FOREMAM
