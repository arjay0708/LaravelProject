$(document).ready(function(){
    totalPendingReservation();
    totalOnGoingReservation();
    totalCompletedReservation();
    totalCustomer();
    getBackOutContent();
    chart();
});


    function totalPendingReservation(){
        $.ajax({
            url: '/totalPendingReservationForAdmin',
            method: 'GET',
            success : function(data) {
                $("#totalPendingReservation").html(data);
            }
        })
    }

    function totalOnGoingReservation(){
        $.ajax({
            url: '/totalOnGoingReservationForAdmin',
            method: 'GET',
            success : function(data) {
                $("#totalOnGoingReservation").html(data);
            }
        })
    }

    function totalCompletedReservation(){
        $.ajax({
            url: '/totalCompletedReservationForAdmin',
            method: 'GET',
            success : function(data) {
                $("#totalCompletedReservation").html(data);
            }
        })
    }

    function totalCustomer(){
        $.ajax({
            url: '/totalCustomerForAdmin',
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

    function chart(){
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
              label: 'Reservation Per Month',
              data: [12, 19, 3, 5, 2, 3],
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
    }

    function chart(){
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
              label: 'Sales Per Month',
              data: [12000, 19000, 3000, 5000, 2000, 3000, 15000, 22000, 13000, 7000, 9000, 13000],
              borderWidth: 1,
              backgroundColor: [
                '#f8d38d',
              ],
                borderColor: [
                    '#f8d38d',
                ],
            }]
          },
          options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max:30000
                },
            }
            }
        });
    }

    // AUTOMATIC DELETE THE UNPAID RESERVATION
    if(window.location.href === 'http://127.0.0.1:8000/adminDashboard'){
        $.ajax({
            url: '/deleteUnpaidReservation',
            type: 'GET',
            dataType: 'json',
        })
        .done(function(response) {
            $('#ongoingReservationTable').DataTable().ajax.reload();
        })
    }
