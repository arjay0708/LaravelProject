$(document).ready(function(){
    availableRoom();
    notAvailableRoom();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
});

// FETCH AVAILABLE ROOM FOR TABLE
    function availableRoom(){
    var table = $('#availableRoom').DataTable({
        "language": {
            "emptyTable": "No Room Found"
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
            "url":"/getAvailableRoom",
            "dataSrc": "",
        },
        "columns":[
            {"data":"room_id"},
            {"data":"room_number"},
            {"data":"floor"},
            {"data":"type_of_room"},
            {"data":"price_per_hour"},
            {"data": "room_id",
                mRender: function (data, type, row) {
                return '<button type="button" data-title="View Details?" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tooltip on top" onclick=viewDetails('+data+') class="btn rounded-0 btn-outline-secondary btn-sm py-2 px-3"><i class="bi bi-pencil-square"></i></button> <button type="button" onclick=deactivateRoom('+data+') class="btn rounded-0 ROUNDED-0 btn-outline-danger btn-sm py-2 px-3" data-title="Deactivate Room?"><i class="bi bi-trash3"></i></button>'
            }
            }
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
// FETCH AVAILABLE ROOM FOR TABLE

// FETCH AVAILABLE ROOM FOR TABLE
    function notAvailableRoom(){
    var table = $('#notAvailableRoom').DataTable({
        "language": {
            "emptyTable": "No Room Found"
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
            "url":"/getNotAvailableRoom",
            "dataSrc": "",
        },
        "columns":[
            {"data":"room_id"},
            {"data":"room_number"},
            {"data":"floor"},
            {"data":"type_of_room"},
            {"data":"price_per_hour"},
            {"data": "room_id",
                mRender: function (data, type, row) {
                return '<button type="button" data-title="View Applicant?" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tooltip on top" onclick=viewApplicants('+data+') class="btn rounded-0 btn-outline-secondary btn-sm py-2 px-3"><i class="bi bi-pencil-square"></i></button> <button type="button" onclick=deactivateApplicants('+data+') class="btn rounded-0 ROUNDED-0 btn-outline-danger btn-sm py-2 px-3" data-title="Deactivate Applicant?"><i class="bi bi-trash3"></i></button>'
            }
            }
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
// FETCH AVAILABLE ROOM FOR TABLE

// ADD ROOM 
    $(document).ready(function () {
        $('#addRoomDetailsForm').on( 'submit' , function(e){
            e.preventDefault();
            var currentForm = $('#addRoomDetailsForm')[0];
            var data = new FormData(currentForm);
                $.ajax({
                    url: "/addRoom",
                    type:"POST",
                    method:"POST",
                    dataType: "text",
                    data:data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success:function(response){
                        if(response == 1){
                            $("#addRoomDetailsForm").trigger("reset");
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'NEW ROOM HAS BEEN STORED',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }else if(response == 0){
                            Swal.fire(
                            'Added Failed',
                            'Sorry room has not stored',
                            'error'
                            )
                        }
                    },
                    error:function(error){
                        console.log(error)
                    }
                }) 
        });
    });
// ADD ROOM
