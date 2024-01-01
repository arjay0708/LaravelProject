$(document).ready(function(){
    pendingReservationTable();
    ongoingReservationTable();
    cancelledReservationTable();
    unpaidReservationTable();
    completedReservationTable();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

// FETCH ALL PENDING RESERVATION FOR TABLES
    function pendingReservationTable(){
    var table = $('#pendingReservationTable').DataTable({
        "language": {
            "emptyTable": "No Reservation Found"
        },
        "lengthChange": true,
        "paging": true,
        "info": true,
        "responsive": true,
        "ordering": false,
        "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
        "iDisplayLength": 25,
        "ajax":{
            "url":"/getAllPendingReservation",
            "dataSrc": "",
        },
        "columns":[
            {"data":"reservation_id"},
            { "mData": function (data, type, row) {
                if(data.extention != null){
                    return data.firstname+ " " +data.lastname+ " " +data.extention;
                }else{
                    return data.firstname+ " " +data.lastname;
                }
            }},
            { "mData": function (data, type, row) {
                    return data.floor+ " - Room " +data.room_number;
            }},
            {"data": "start_dataTime",
                "render": function(data) {
                return moment(data).format('MMM DD, YYYY | hh:mm A');
            },
            "targets": 1
            },
            {"data": "end_dateTime",
                "render": function(data) {
                return moment(data).format('MMM DD, YYYY | hh:mm A');
            },
            "targets": 1
            },
            { "mData": function (data, type, row) {
                return "₱"+data.totalPayment+ ".00" ;
            }},
            { "mData": function (data, type, row) {
                return "₱"+data.balance+ ".00" ;
            }},
            { "mData": function (data, type, row) {
                return '<button type="button" data-title="Set Reservation?" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tooltip on top" onclick="ongoingReservation('+data.reservation_id+', '+data.room_id+')" class="btn rounded-0 btn-outline-secondary btn-sm py-2 px-3"><i class="bi bi-check2-square"></i></button></button>'
                // return '<button type="button" data-title="Accept Reservation?" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tooltip on top" onclick=acceptReservation('+data.reservation_id+') class="btn rounded-0 btn-outline-success btn-sm py-2 px-3"><i class="bi bi-check2-square"></i></button> <button type="button" onclick="declineReservation('+data.reservation_id+', '+data.user_id+')" class="btn rounded-0 ROUNDED-0 btn-outline-danger btn-sm py-2 px-3" data-title="Decline Reservation?"><i class="bi bi-x-square"></i></button>'
            }},
        ],
        order: [[1, 'asc']],
    });
    table.on('order.dt search.dt', function () {
        let i = 1;
        table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
        });
    }).draw();
    }
// FETCH ALL PENDING RESERVATION FOR TABLES

// FETCH ALL ACCEPT RESERVATION FOR TABLE
    function ongoingReservationTable(){
        var table = $('#ongoingReservationTable').DataTable({
            "language": {
                "emptyTable": "No Reservation Found"
            },
            "lengthChange": true,
            "scrollCollapse": true,
            "paging": true,
            "info": true,
            "responsive": true,
            "ordering": false,
            "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
            "iDisplayLength": 25,
            "ajax":{
                "url":"/getAllOnGoingReservation",
                "dataSrc": "",
            },
            "columns":[
                {"data":"reservation_id"},
                { "mData": function (data, type, row) {
                    if(data.extention != null){
                        return data.firstname+ " " +data.lastname+ " " +data.extention;
                    }else{
                        return data.firstname+ " " +data.lastname;
                    }
                }},
                { "mData": function (data, type, row) {
                        return data.floor+ " - Room " +data.room_number;
                }},
                {"data": "start_dataTime",
                    "render": function(data) {
                    return moment(data).format('MMM DD, YYYY | hh:mm A');
                },
                "targets": 1
                },
                {"data": "end_dateTime",
                    "render": function(data) {
                    return moment(data).format('MMM DD, YYYY | hh:mm A');
                },
                "targets": 1
                },
                { "mData": function (data, type, row) {
                    return "₱"+data.totalPayment+ ".00" ;
                }},
                { "mData": function (data, type, row) {
                    return "₱"+data.balance+ ".00" ;
                }},
                { "mData": function (data, type, row) {
                    return '<button type="button" data-title="Complete Transaction?" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tooltip on top" onclick="completeTransaction('+data.reservation_id+' , '+data.roomId+')" class="btn rounded-0 btn-outline-success btn-sm py-2 px-3"><i class="bi bi-check2-square"></i></button>'
                }},
            ],
            order: [[1, 'asc']],
        });
        table.on('order.dt search.dt', function () {
            let i = 1;
            table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                this.data(i++);
            });
        }).draw();
    }
// FETCH ALL ACCEPT RESERVATION FOR TABLE

// FETCH ALL CANCELLED RESERVATION FOR TABLE
    function cancelledReservationTable(){
        var table = $('#cancelledReservationTable').DataTable({
            "language": {
                "emptyTable": "No Reservation Found"
            },
            "lengthChange": true,
            "scrollCollapse": true,
            "paging": true,
            "info": true,
            "responsive": true,
            "ordering": false,
            "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
            "iDisplayLength": 25,
            "ajax":{
                "url":"/getAllCancelledReservation",
                "dataSrc": "",
            },
            "columns":[
                {"data":"reservation_id"},
                { "mData": function (data, type, row) {
                    if(data.extention != null){
                        return data.firstname+ " " +data.lastname+ " " +data.extention;
                    }else{
                        return data.firstname+ " " +data.lastname;
                    }
                }},
                { "mData": function (data, type, row) {
                        return data.floor+ " - Room " +data.room_number;
                }},
                {"data": "start_dataTime",
                    "render": function(data) {
                    return moment(data).format('MMM DD, YYYY | hh:mm A');
                },
                "targets": 1
                },
                {"data": "end_dateTime",
                    "render": function(data) {
                    return moment(data).format('MMM DD, YYYY | hh:mm A');
                },
                "targets": 1
                },
                { "mData": function (data, type, row) {
                    return "₱"+data.totalPayment+ ".00" ;
                }},
                { "mData": function (data, type, row) {
                    return "₱"+data.balance+ ".00" ;
                }},
                { "mData": function (data, type, row) {
                    return '<button type="button" data-title="View Reason?" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tooltip on top" onclick="viewReason('+data.reservation_id+', '+data.room_id+')" class="btn rounded-0 btn-outline-secondary btn-sm py-2 px-3"><i class="bi bi-chat-dots"></i></button>'
                }},
            ],
            order: [[1, 'asc']],
        });
        table.on('order.dt search.dt', function () {
            let i = 1;
            table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                this.data(i++);
            });
        }).draw();
    }
// FETCH ALL CANCELLED RESERVATION FOR TABLE

// FETCH ALL DECLINE RESERVATION FOR TABLE
    function unpaidReservationTable(){
        var table = $('#unpaidReservationTable').DataTable({
            "language": {
                "emptyTable": "No Reservation Found"
            },
            "lengthChange": true,
            "scrollCollapse": true,
            "paging": true,
            "info": true,
            "responsive": true,
            "ordering": false,
            "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
            "iDisplayLength": 25,
            "ajax":{
                "url":"/getAllUnpaidReservation",
                "dataSrc": "",
            },
            "columns":[
                {"data":"reservation_id"},
                { "mData": function (data, type, row) {
                    if(data.extention != null){
                        return data.firstname+ " " +data.lastname+ " " +data.extention;
                    }else{
                        return data.firstname+ " " +data.lastname;
                    }
                }},
                { "mData": function (data, type, row) {
                        return data.floor+ " - Room " +data.room_number;
                }},
                {"data": "start_dataTime",
                    "render": function(data) {
                    return moment(data).format('MMM DD, YYYY | hh:mm A');
                },
                "targets": 1
                },
                {"data": "end_dateTime",
                    "render": function(data) {
                    return moment(data).format('MMM DD, YYYY | hh:mm A');
                },
                "targets": 1
                },
                { "mData": function (data, type, row) {
                    return "₱"+data.totalPayment+ ".00" ;
                }},
                { "mData": function (data, type, row) {
                    return "₱"+data.balance+ ".00" ;
                }},
            ],
            order: [[1, 'asc']],
        });
        table.on('order.dt search.dt', function () {
            let i = 1;
            table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
                this.data(i++);
            });
        }).draw();
    }
// FETCH ALL DECLINE RESERVATION FOR TABLE

// FETCH ALL COMPLETED TRANSACTION
    function completedReservationTable(){
    var table = $('#completedReservationTable').DataTable({
        "language": {
            "emptyTable": "No Reservation Found"
        },
        "lengthChange": true,
        "scrollCollapse": true,
        "paging": true,
        "info": true,
        "responsive": true,
        "ordering": false,
        "aLengthMenu": [[25, 50, 75, -1], [25, 50, 75, "All"]],
        "iDisplayLength": 25,
        "ajax":{
            "url":"/getAllCompletedReservation",
            "dataSrc": "",
        },
        "columns":[
            {"data":"reservation_id"},
            { "mData": function (data, type, row) {
                if(data.extention != null){
                    return data.firstname+ " " +data.lastname+ " " +data.extention;
                }else{
                    return data.firstname+ " " +data.lastname;
                }
            }},
            { "mData": function (data, type, row) {
                    return data.floor+ " - Room " +data.room_number;
            }},
            {"data": "start_dataTime",
                "render": function(data) {
                return moment(data).format('MMM DD, YYYY | hh:mm A');
            },
            "targets": 1
            },
            {"data": "end_dateTime",
                "render": function(data) {
                return moment(data).format('MMM DD, YYYY | hh:mm A');
            },
            "targets": 1
            },
            { "mData": function (data, type, row) {
                return "₱"+data.totalPayment+ ".00" ;
            }},
        ],
        order: [[1, 'asc']],
    });
    table.on('order.dt search.dt', function () {
        let i = 1;
        table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
        });
    }).draw();
    }
// FETCH ALL COMPLETED TRANSACTION

// SET RESERVATION
    function ongoingReservation(reservationId , roomId){
        Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to SET this BOOKING?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Accept it'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
            url: '/ongoingReservation',
            type: 'GET',
            dataType: 'json',
            data: {reservationId: reservationId, roomId: roomId},
            success: function(response) {
                if(response == 1){
                    Swal.fire({
                        title: 'ON-GOING BOOKING',
                        text: "Reservation was set to ON-GOING successfully",
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500,
                    }).then((result) => {
                    if (result) {
                        $('#ongoingReservationTable').DataTable().ajax.reload();
                    }
                    });
                }else if(response == 2){
                    Swal.fire({
                        icon: 'error',
                        title: 'Set Failed',
                        text: 'The BOOKING was not reach the Check In Date and Time',
                    })
                }
            }
        });
        }
        });
    }
// SET RESERVATION

// COMPLETE TRANSACTION
    function completeTransaction(reservationId){
        Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to COMPLETE this BOOKING?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Accept it'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
            url: '/completeReservation',
            type: 'GET',
            dataType: 'json',
            data: {reservationId: reservationId},
            success: function(response) {
                if(response == 1){
                    Swal.fire({
                        title: 'COMPLETE BOOKING',
                        text: "Reservation was COMPLETE successfully",
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500,
                    }).then((result) => {
                    if (result) {
                        $('#ongoingReservationTable').DataTable().ajax.reload();
                    }
                    });
                }else if(response == 2){
                    Swal.fire({
                        icon: 'error',
                        title: 'Complete Failed',
                        text: 'The reservation was not done yet',
                    })
                }
            }
        });
        }
        });
    }
// COMPLETE TRANSACTION

// VIEW CANCELLED REASON
    function viewReason(id){
        $('#cancelledReasonModal').modal('show')
        $.ajax({
            url: '/viewReasonCancelled',
            type: 'GET',
            dataType: 'json',
            data: {reservationId: id},
        })
        .done(function(response) {
            $('#cancelledReason').text(response.reason);
            $('#cancelledLast').text(moment(response.created_at).format('MMM DD, YYYY | hh:mm A'));
        })
    }
// VIEW CANCELLED REASON
