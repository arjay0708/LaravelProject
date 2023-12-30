$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

// FUNCTION FOR CANCEL BOOKING
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
                async function readNotesAndRemarks() {
                    const { value: accept } = await Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to continue to cancel this reservation?",
                        icon: 'question',
                        input: "checkbox",
                        inputValue: 1,
                        inputPlaceholder: `
                        I read the <a href="#">notes and remarks</a> before cancelling my booking.
                        `,
                        confirmButtonText: `
                        Continue&nbsp;<i class="fa fa-arrow-right"></i>
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
                            showBookingPerUser();
                        }
                    });
                    }
                }
                readNotesAndRemarks();
                showBookingPerUser();
                showDeclineBookingPerUser();
            }
        });
    }
// FUNCTION FOR CANCEL BOOKING

// FUNCTION FOR BACK OUT RESERVATION
    function backOutReservation(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to BACK OUT this BOOKING?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d72323',
            confirmButtonText: 'Yes, Continue'
            }).then((result) => {
            if (result.isConfirmed) {
            (async () => {
                const { value: reason } = await Swal.fire({
                    input: 'textarea',
                    title: 'Reason of Back Out?',
                    text: "once you submit, You won't be able to revert this",
                    inputPlaceholder: 'Type your reason here...',
                    inputAttributes: {
                    'aria-label': 'Type your message here'
                    },
                    showCancelButton: true
                })
                if(reason){
                    $.ajax({
                        url: '/backOutReservation',
                        type: 'GET',
                        dataType: 'text',
                        data: {reason: reason, reservationId: id},
                        success: function(response) {
                            if(response == 1){
                                Swal.fire({
                                    title: 'BACK OUT SUCCESSFULLY',
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1000,
                                }).then((result) => {
                                if (result) {
                                    showAcceptBookingPerUser();                                }
                                });
                            }else if(response == 0){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Back Out Failed',
                                    text: 'Something wrong at the backend',
                                })
                            }
                        }
                    });
                }
            })()
            }
        });
    }
// FUNCTION FOR BACK OUT RESERVATION
