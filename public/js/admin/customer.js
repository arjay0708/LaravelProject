$(document).ready(function(){
    customerTable();
    inactiveCustomerTable();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
});

// FETCH ACTIVE APPLICANTS FOR TABLES
    function customerTable(){
    var table = $('#activeCustomer').DataTable({
        "language": {
            "emptyTable": "No Customer Found"
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
            "url":"/getActiveCustomer",
            "dataSrc": "",
        },
        "columns":[
            {"data":"user_id"},
            { "mData": function (data, type, row) {
                if(data.extention != null){
                    return data.firstname+ " " +data.lastname+ " " +data.extention;
                }else{
                    return data.firstname+ " " +data.lastname;
                }
            }},
            {"data":"email"},
            {"data":"phoneNumber"},
            {"data": "user_id",
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
// FETCH ACTIVE APPLICANTS FOR TABLES

// INACTIVE APPLICANTS FOR TABLE
    function inactiveCustomerTable(){
        var table = $('#inactiveCustomer').DataTable({
            "language": {
                "emptyTable": "No Customer Found"
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
                "url":"/getInActiveCustomer",
                "dataSrc": "",
            },
            "columns":[
                {"data":"user_id"},
                { "mData": function (data, type, row) {
                    if(data.extention != null){
                        return data.firstname+ " " +data.lastname+ " " +data.extention;
                    }else{
                        return data.firstname+ " " +data.lastname;
                    }
                }},                {"data":"email"},
                {"data":"phoneNumber"},
                {"data": "user_id",
                    mRender: function (data, type, row) {
                    return '<button type="button" data-title="View Applicant?" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tooltip on top" onclick=viewApplicants('+data+') class="btn rounded-0 btn-outline-success btn-sm py-2 px-3"><i class="bi bi-pencil-square"></i></button> <button type="button" onclick=deactivateApplicants('+data+') class="btn rounded-0 ROUNDED-0 btn-outline-danger btn-sm py-2 px-3" data-title="Deactivate Applicant?"><i class="bi bi-trash3"></i></button>'
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
// INACTIVE APPLICANTS FOR TABLE

// FETCH DATA FOR UPDATE OPERATION
    function viewApplicants(id){
        $('#viewApplicantsDetails').modal('show')
        $.ajax({
            url: '/viewApplicants',
            type: 'GET',
            dataType: 'json',
            data: {applicantId: id},
        })
        .done(function(response) {
            if(response.photos != ''){
                $('#applicantsPhoto').attr("src",response.photos)
            }else{
                $('#applicantsPhoto').attr("src","/assets/applicants/defaultImage.png")
            }
            $('#applicantsLastname').val(response.lastname)           
            $('#applicantsFirstname').val(response.firstname)           
            $('#applicantsMiddlename').val(response.middlename)
            $('#applicantsExt').val(response.extention)           
            $('#applicantsPosition').val(response.position)           
            $('#applicantsStatus').val(response.status)           
            $('#applicantsSex').val(response.Gender)           
            $('#applicantsAge').val(response.age)           
            $('#applicantsBirthday').val(response.birthday)           
            $('#applicantsAddress').val(response.address)           
            $('#applicantsPnumber').val(response.phoneNumber)           
            $('#applicantsEmail').val(response.emailAddress)           
            $('#applicantsNationality').val(response.nationality)           
            $('#applicantsReligion').val(response.religion)           
        })
    }
// FETCH DATA FOR UPDATE OPERATION

// DEACTIVATE APPLICANTS ACCOUNT
    function deactivateApplicants(id){
        Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to DEACTIVATE this applicant?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, deactivate it'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
            url: '/deactivateApplicants',
            type: 'GET',
            dataType: 'json',
            data: {applicantId: id},
        });
        Swal.fire({
            title: 'Change Status',
            text: "Applicants was DEACTIVATE successfully",
            icon: 'success',
            showConfirmButton: false,
            timer: 1500,
        }).then((result) => {
        if (result) {
            $('#applicants').DataTable().ajax.reload();
        }
        });
        }
        });
    } 
// DEACTIVATE APPLICANTS ACCOUNT

// ACTIVATE APPLICANTS ACCOUNT
    function activateApplicants(id){
        Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to activate this applicant?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, activate it'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
            url: '/activateApplicant',
            type: 'GET',
            dataType: 'json',
            data: {applicantId: id},
        });
        Swal.fire({
            title: 'Change Status',
            text: "Applicants was deactivate successfully",
            icon: 'success',
            showConfirmButton: false,
            timer: 1500,
        }).then((result) => {
        if (result) {
            $('#inactiveApplicants').DataTable().ajax.reload();
        }
        });
        }
        });
    } 
// ACTIVATE APPLICANTS ACCOUNT