$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    showBookingPerUser();
    showAcceptBookingPerUser();
    showDeclineBookingPerUser();
});

// SHOW PENDING RESERVATION PER USER
    function showBookingPerUser(){
        $.ajax({
            url: "/getBookPerUser",
            method: 'GET',
            success : function(data) {
                $("#showPendingReservation").html(data);
            }
        })
    }
// SHOW PENDING RESERVATION PER USER

// SHOW ACCEPT RESERVATION PER USER 
    function showAcceptBookingPerUser(){
        $.ajax({
            url: "/getAcceptBookPerUser",
            method: 'GET',
            success : function(data) {
                $("#showAcceptReservation").html(data);
            }
        })
    }
// SHOW ACCEPT RESERVATION PER USER 

// SHOW DECLINED RESERVATION PER USER
    function showDeclineBookingPerUser(){
        $.ajax({
            url: "/getDeclineBookPerUser",
            method: 'GET',
            success : function(data) {
                $("#showDeclineReservation").html(data);
            }
        })
    }
// SHOW DECLINED RESERVATION PER USER

// FUNCTION FOR BOOKING
    function cancelReservation(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to CANCEL this RESERVATION?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d72323',
            confirmButtonText: 'Yes, Cancel it'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                url: '/cancelReservation',
                type: 'GET',
                dataType: 'json',
                data: {reservationID: id},
            });
            Swal.fire({
                title: 'CANCEL SUCCESSFULLY',
                icon: 'success',
                showConfirmButton: false,
                timer: 1000,
            }).then((result) => {
            if (result) {
                showTotalRoom();            
            }
            });
            }
        });
    }
// FUNCTION FOR BOOKING