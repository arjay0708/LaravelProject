$(document).ready(function(){
    totalPendingReservation();
    totalOnGoingReservation();
    totalCompletedReservation();
    totalCustomer();
    getBackOutContent();
});


    function totalPendingReservation(){
        $.ajax({
            url: '/totalPendingReservation',
            method: 'GET',
            success : function(data) {
                $("#totalPendingReservation").html(data);
            }
        })
    }

    function totalOnGoingReservation(){
        $.ajax({
            url: '/totalOnGoingReservation',
            method: 'GET',
            success : function(data) {
                $("#totalOnGoingReservation").html(data);
            }
        })
    }

    function totalCompletedReservation(){
        $.ajax({
            url: '/totalCompletedReservation',
            method: 'GET',
            success : function(data) {
                $("#totalCompletedReservation").html(data);
            }
        })
    }

    function totalCustomer(){
        $.ajax({
            url: '/totalCustomer',
            method: 'GET',
            success : function(data) {
                $("#totalCustomer").html(data);
            }
        })
    }

    function getBackOutContent(){
        $.ajax({
            url: '/getBackOutContentForAdmin',
            method: 'GET',
            success : function(data) {
                $("#fetchAllBackOut").html(data);
            }
        })
    }

    function noteBackOutContent(id){
        Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to NOTE this LETTER?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Note it'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
            url: '/archivedCancelledReservation',
            type: 'GET',
            dataType: 'json',
            data: {reservationId: id},
        });
        Swal.fire({
            title: 'ACCEPT BOOKING',
            text: "Reservation was accept successfully",
            icon: 'success',
            showConfirmButton: false,
            timer: 1500,
        }).then((result) => {
        if (result) {
            getBackOutContent();        
        }
        });
        }
        });
    } 
