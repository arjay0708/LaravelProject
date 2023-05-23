$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

// FUNCTION FOR PASSWORD ENABLE
    function seePassword() {
        var x = document.getElementById("userPassword");
        var a = document.getElementById("userConPassword");
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
    function seePassword2() {
        var x = document.getElementById("userPassword");
        if (x.type === 'password'){
            x.type ="text";
        }else{
            x.type="password";
        }
        
    }
// FUNCTION FOR PASSWORD ENABLE


// SIGN UP FUNCTION
    $('#registrationForm').on( 'submit' , function(e){
        e.preventDefault();
        var email = $('#userEmail').val();
        var password = $('#userPassword').val();
        var confirmPassword = $('#userConPassword').val();
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

// LOGIN FUNCTION
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
            var parsed = JSON.parse(response);
            if(response == 1){
                    $('#loginForm').trigger("reset");
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
            }
            if(response == 2){
                $('#loginForm').trigger("reset");
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
                        window.location = "/customerDashboardRoutes";
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
                'Wrong Username/Password',
                'error'
                )
            }else if(response == 3){
                Swal.fire(
                'Inactive Account',
                'Your account is disable to access the A&S Application',
                'error'
                )
            }
            });
    });
// LOGIN FUNCTION