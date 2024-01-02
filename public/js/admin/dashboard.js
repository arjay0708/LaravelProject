$(document).ready(function(){
    getAllTotalForAdmin();
    chart();
    checkCancelledReservation();
});

    function getAllTotalForAdmin(){
        $.ajax({
            url: '/getAllTotalForAdmin',
            method: 'GET',
            success : function(data) {
                $("#totalPendingReservation").html(data.totalPendingReservation);
                $("#totalOnGoingReservation").html(data.totalOnGoingReservation);
                $("#totalCompletedReservation").html(data.totalCompletedReservation);
                $("#totalCustomer").html(data.totalCustomer);

            }
        })
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

    function checkCancelledReservation(){
        $.ajax({
            url: '/checkCancelledReservation',
            type: 'GET',
            dataType: 'json',
        })
        .done(function(response) {
            if(response === 1){
                Swal.fire({
                    position: "top-center",
                    icon: "warning",
                    title: "SOMEONE CANCELLED THEIR BOOKING",
                    footer: '<a href="/adminCancelledReservation">REDIRECT TO CANCELLED RESERVATION PAGE?</a>'
                });
            }
        })
    }
