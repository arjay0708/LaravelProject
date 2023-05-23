<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @include('cdn')
    <title>Harbor View</title>
</head>
<body>
    {{-- NAVBAR --}}
       <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="login">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/">Home</a>
            </li>
            </ul>
        </div>
        </div>
        </nav>
    {{-- NAVBAR --}}

    {{-- MAIN  --}}
        <div class="container">
            <form name="registrationForm" id="registrationForm">
                @csrf
                <p class="title mt-lg-3">CREATE ACCOUNT</p>
                <div class="mb-3">
                    <input type="email" class="form-control " required id="userEmail" name="userEmail" placeholder="Email">
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control " required id="userPassword" name="userPassword" placeholder="Password">
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control " required id="userConPassword" name="userConPassword" placeholder="Confirm Password">
                </div>
                <div class="mb-3 checkBox ms-1">
                    <input type="checkbox" class="form-check-input ms-1" onclick="seePassword()">
                    <label class="form-check-label ms-4">Show Password</label>
                </div>
                    <button type="submit" class="btn btn-primary">SUBMIT</button>
                <ul class="navbar-nav text-center">
                    <li class="nav-item"><a href="/login" class="nav-link bottomLink">Already Have an Account?</a></li>
                </ul>
            </form>
        </div>
    {{-- MAIN  --}}

    {{-- JS --}}
        <script src="{{ asset('/js/auth.js') }}"></script>
    {{-- JS --}}
</body>
</html>