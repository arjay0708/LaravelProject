$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    showTotalRoom();
});

// SHOW TOTAL ROOM
    function showTotalRoom(){
        $.ajax({
            url: "/getCustomerRoom",
            method: 'GET',
            success : function(data) {
                $("#showTotalRoom").html(data);
            }
        })
    }
// SHOW TOTAL ROOM

// FUNCTION FOR BOOKING
    function bookReservation(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to BOOK this room?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d72323',
            confirmButtonText: 'Yes, Continue!'
            }).then((result) => {
            if (result.isConfirmed) {
                $('#reservationModal').modal('show')
                $('#submitDateBooking').click(function(){
                    var checkInDateTime = $("#checkInDateTime").val();
                    var checkOutDateTime = $("#checkOutDateTime").val();
                    $.ajax({
                        url: "/bookReservation",
                        type:"POST",
                        method:"POST",
                        dataType: "text",
                        data: {roomId: id, checkInDateTime:checkInDateTime, checkOutDateTime:checkOutDateTime},
                        success: function(response) {
                            if (response == 1) {
                                showTotalRoom();
                                $("#bookReservationForm").trigger("reset");
                                $('#reservationModal').modal('hide');
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'RESERVATION HAS BEEN SUBMITTED',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            } else if (response == 0) {
                                Swal.fire('Addition Failed', 'Sorry, the operation has not been stored', 'error');
                            } else if (response == 4) {
                                Swal.fire('Invalid Check In', 'Please check the date and time of the CHECK IN', 'error');
                            } else if (response == 3) {
                                Swal.fire('Invalid Check Out', 'Please check the date and time of the CHECK OUT', 'error');
                            } else if (response == 2) {
                                Swal.fire('Invalid Date and Time', 'The date of both CHECK IN and CHECK OUT must not be the same', 'error');
                            } else if (response == 6) {
                                Swal.fire('BOOK FAILED', 'You are already reserved with the same date and time', 'error');
                            } else if (response == 5) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'BOOK FAILED',
                                    text: 'Please complete all of your information.',
                                    footer: '<a href="/customerAccount">DIRECT ME TO MANAGE ACCOUNT</a>'
                                });
                            } else {
                                Swal.fire('Unknown Response', 'An unexpected response was received', 'error');
                            }
                        },
                        
                        error:function(error){
                            console.log(error)
                        }
                    })
                });
            }
        });
    }
// FUNCTION FOR BOOKING

// FUNCTION FOR BOOKING
    function cancelReservation(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to CANCELs this RESERVATION?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d72323',
            confirmButtonText: 'Yes, Cancel it'
            }).then((result) => {
            if (result.isConfirmed) {
                async function cancelReservation() {
                    const { value: accept } = await Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to continue to cancelling this reservation?",
                        icon: 'question',
                        input: "checkbox",
                        inputValue: 1,
                        inputPlaceholder: `
                        I read the notes and remark before cancelling my reservation.
                        `,
                        confirmButtonText: `
                        Continue;
                        `,
                        inputValidator: (result) => {
                            return !result && "You need to read the notes and remarks before cancelling your reservation";
                        }
                    });
                    if (accept) {
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
                }
                cancelReservation();
            }
        });
    }
// FUNCTION FOR BOOKING



