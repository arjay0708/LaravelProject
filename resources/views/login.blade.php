<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{ URL('/img/whitelogo.png')}}" type="image/x-icon">
    @include('cdn')
    <title>Harbor View</title>
</head>
<body>
        {{-- CONTENT --}}
            <div class="back-image">
                <img src="./img/img1.png" alt="background image">
            </div>

        {{-- FOR REGISTRATION --}}
            <section class="left">
                <section class="main register">
                    <div class="container mt-5 pt-5">
                        <a class='homeButton2' href="/" data-title='Back to Home?'><i class="bi bi-house"></i></a>
                        <img class="border-0 logo" src="{{ URL('/img/whitelogo.png')}}">
                        <p class="title mt-lg-3">CREATE ACCOUNT</p>
                        <form name="registrationForm" id="registrationForm">
                            @csrf
                            <div class="mb-3">
                                <input type="email" class="form-control rounded-pill" required id="userRegisterEmail" name="userRegisterEmail" placeholder="Email">
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control rounded-pill" required id="userRegisterPassword" name="userRegisterPassword" placeholder="Password">
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control rounded-pill" required id="userRegisterConPassword" name="userRegisterConPassword" placeholder="Confirm Password">
                            </div>
                            <div class="mb-3 checkBox ms-1">
                                <input type="checkbox" class="form-check-input ms-1" onclick="seePassword()">
                                <label class="form-check-label ms-4">Show Password</label>
                            </div>
                                <button type="submit" class="btn rounded-pill">SUBMIT</button>
                            <ul class="navbar-nav text-center">
                                <li class="nav-item"><a href="#" class="nav-link bottomLink">Already Have an Account?</a></li>
                            </ul>
                        </form>
                    </div>
                </section>
            </section>
        {{-- FOR REGISTRATION --}}

        {{-- FOR LOGIN --}}
            <section class="right">
                <section class="main login">
                    <div class="container mt-5 pt-5">
                        <a class='homeButton' href="/" data-title='Back to Home?'><i class="bi bi-house"></i></a>
                        <img class="border-0 logo" src="{{ URL('/img/whitelogo.png')}}">
                        <p class="title mt-lg-3">HARBOR VIEW RESORT</p>
                        <form name="userLoginForm" id="userLoginForm">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" name="userEmail" id="userEmail" placeholder="Email" required>
                                <label for="floatingInput" class="text-muted">Email</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" name="userLoginPassword" id="userLoginPassword" placeholder="Password" required>
                                <label for="floatingInput" class="text-muted">Password</label>
                            </div>
                            <div class="mb-3 checkBox ms-4">
                                <input type="checkbox" class="form-check-input" onclick="seePassword2()">
                                <label class="form-check-label">Show Password</label>
                            </div>
                            <ul class="navbar-nav text-center">
                                <li class="nav-item"><a href="/forgotPasswordRoutes" class="nav-link">Forgot Password?</a></li>
                            </ul>
                                <button type="submit" id="appLoginBtn" name="appLoginBtn" class="btn rounded-pill">LOGIN</button>
                            <ul class="navbar-nav text-center">
                                <li class="nav-item"><a href="#" class="nav-link bottomLink">Create Your Account</a></li>
                            </ul>
                        </form>
                    </div>
                </section>
            </section>
        {{-- FOR LOGIN --}}
    {{-- END OF CONTENT --}}

    {{-- JS --}}
        <script src="{{ asset('/js/auth.js') }}"></script>
    {{-- JS --}}
</body>
</html>
