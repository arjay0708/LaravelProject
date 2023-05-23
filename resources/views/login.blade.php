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
                        <a class="nav-link active" aria-current="page" href="registration">Registration</a>
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
        <div class="container my-5">
            <form name="userLoginForm" id="userLoginForm">
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" name="userEmail" id="userEmail" placeholder="Email" required>
                    <label for="floatingInput" class="text-muted">Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="userPassword" id="userPassword" placeholder="Password" required>
                    <label for="floatingInput" class="text-muted">Password</label>
                </div>
                <div class="mb-3 checkBox ms-4">
                    <input type="checkbox" class="form-check-input" onclick="seePassword2()">
                    <label class="form-check-label">Show Password</label>
                </div>
                <ul class="navbar-nav text-center">
                    <li class="nav-item"><a href="/forgotPasswordRoutes" class="nav-link">Forgot Password?</a></li>
                </ul>
                    <button type="submit" id="appLoginBtn" name="appLoginBtn" class="btn btn-primary rounded-pill">LOGIN</button>
                <ul class="navbar-nav text-center">
                    <li class="nav-item"><a href="/registration" class="nav-link bottomLink">Create Your Account</a></li>
                </ul>
            </form>
        </div>
    {{-- MAIN  --}}
    
    {{-- JS --}}
        <script src="{{ asset('/js/auth.js') }}"></script>
    {{-- JS --}}
</body>
</html>