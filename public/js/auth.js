$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

// FUNCTION FOR PASSWORD ENABLE
    function seePasswordUserRegistration() {
        var x = document.getElementById("userRegisterPassword");
        var a = document.getElementById("userRegisterConPassword");
        if (x.type === 'password' && a.type === 'password'){
            x.type ="text";
            a.type ="text";
        }else{
            x.type="password";
            a.type="password";
        }

    }
// FUNCTION FOR PASSWORD ENABLE

// FUNCTION FOR PASSWORD ENABLE
    function seePasswordUserLogin() {
        var x = document.getElementById("userLoginPassword");
        if (x.type === 'password'){
            x.type ="text";
        }else{
            x.type="password";
        }

    }
// FUNCTION FOR PASSWORD ENABLE

// FUNCTION FOR PASSWORD ENABLE IN ADMIN LOGIN
    function seePasswordAdminLogin() {
        var x = document.getElementById("adminPassword");
        if (x.type === 'password'){
            x.type ="text";
        }else{
            x.type="password";
        }
    }
// FUNCTION FOR PASSWORD ENABLE IN ADMIN LOGIN

// SIGN UP FUNCTION
    $('#registrationForm').on( 'submit' , function(e){
        e.preventDefault();
        var email = $('#userRegisterEmail').val();
        var password = $('#userRegisterPassword').val();
        var confirmPassword = $('#userRegisterConPassword').val();
        if(password < 6 || password > 20){
            Swal.fire(
                'PASSWORD FAILED',
                'The password must be longer than 6 characters and less than 20 characters',
                'error'
            )
        }
        else if(password != confirmPassword){
            Swal.fire(
                'PASSWORD MISMATCH',
                'Please, check your password',
                'error'
            )
        }else if(password === 'password'){
            Swal.fire(
                'PASSWORD FAILED',
                'The password can not be set to password',
                'error'
            )
        }else{
            $.ajax({
                url:"/registrationFunction",
                method:"POST",
                dataType:"text",
                data:{email:email,password:password},
                success: function(response) {
                if(response == 1){
                Swal.fire({
                icon: 'success',
                title: 'REGISTER SUCCESSFULLY',
                showConfirmButton: false,
                timer: 1500
                }).then((result) => {
                if (result) {
                    $("#registrationForm").trigger("reset");
                    window.location.href = "/login";
                }
                })
                }
                else if(response == 0){
                    Swal.fire(
                    'SORRY REGISTRATION FAILED',
                    'Sorry, please re-enter your credentials',
                    'error'
                    )
                }
                else if(response == 2){
                    Swal.fire(
                    'EMAIL ADDRESS NOT AVAILABLE',
                    'Sorry, please choose another valid email',
                    'error'
                    )
                }
                },
                error:function(er){
                console.log(er)
                }
            });
        }
    });
// SIGN UP FUNCTION

// USER LOGIN FUNCTION
    $('#userLoginForm').on( 'submit' , function(e){
        e.preventDefault();
        var data = $('#userLoginForm').serialize();
        $.ajax({
        url:"/userLoginFunction",
        method:"POST",
        dataType:"text",
        data:data
        })
        .done(function(response) {
            if(response == 1){
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    },
                    didClose: () =>{
                        window.location = "/customerDashboard";
                    }
                    })
                    Toast.fire({
                    icon: 'success',
                    title: 'Signed in successfully',
                    text: 'Welcome Customer',
                    })
            }else if(response == 0){
                Swal.fire(
                'Sorry Login Failed',
                'Wrong Email or Password',
                'error'
                )
            }else if(response == 3){
                Swal.fire(
                'Inactive Account',
                'Your account is disable to access the A&S Application',
                'error'
                )
            }else if(response == 2){
                Swal.fire(
                'Sorry Login Failed',
                'Email verification required. Please check your inbox to verify it.',
                'error'
                )
            }
            $('#userLoginForm')[0].reset()
        });
    });

    setTimeout(function() {
        $('.alert.alert-success').hide();
    }, 5000);
// USER LOGIN FUNCTION

// ADMIN LOGIN FUNCTION
    $('#adminLoginForm').on( 'submit' , function(e){
    e.preventDefault();
    var data = $('#adminLoginForm').serialize();
    $.ajax({
        url:"/adminLoginFunction",
        method:"POST",
        dataType:"text",
        data:data
    })
    .done(function(response) {
        if(response == 1){
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                },
                didClose: () =>{
                    window.location = "/adminDashboard";
                }
                })
                Toast.fire({
                icon: 'success',
                title: 'Signed in successfully',
                text: 'Welcome Admin',
                })
        }else if(response == 0){
            Swal.fire(
            'Sorry Login Failed',
            'Wrong Email or Password',
            'error'
            )
        }
        $('#adminLoginForm')[0].reset()
    });
    });
// ADMIN LOGIN FUNCTION

// LISTENER
    var sideButtons = document.querySelectorAll('.bottomLink');
        sideButtons.forEach(btn => btn.addEventListener('click', () => {
        document.body.classList.toggle('signup');
    }))
// LISTENER
