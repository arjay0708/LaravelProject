<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @include('cdn')
    <title>HarborView Beach Resort</title>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top" id="banner">
        <div class="container">
      <!-- Brand -->
      {{-- <a class="navbar-brand" href="#"><span>Logo</span> Here</a> --}}
    
      <!-- Toggler/collapsibe Button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
    
      <!-- Navbar links -->
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav ml-auto">s
          <li class="nav-item">
            <a class="nav-link" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Offers & Programs</a>
          </li> 
          <li class="nav-item">
            <a class="nav-link" href="#">Contact Us</a>
          </li> 
          <li class="nav-item">
            <a class="nav-link" href="/login">Log In</a>
          </li> 
        
        </ul>
      </div>
        </div>
    </nav>
    
    <div class="banner" style="background-image: url('{{ asset('img/mainback.jpg') }}')">
        <div class="container">
        <div class="banner-text">
        <div class="banner-heading">
        WELCOME TO
        </div>
        <img src="img/whitelogo.png" style="height:40vh;">
        <div class="banner-sub-heading">
        HARBORVIEW BEACH RESORT
        </div>
        {{-- <button type="button" class="btn btn-warning text-dark btn-banner">Get started</button> --}}
        </div>
        </div>
    </div>
    <section id="about">
    <div class="container">
        <div class="row row-height">
            <div class="col-sm-4">
              <div class="img-box">
                <img src="img/img1.png" alt="about">
              </div>
              <!-- end img box -->
            </div>

            <div class="col-sm-8 col-custom">
              <div class="container abt-caption">
                <h1 class="abt-title pb-3">Welcome to Harborview Beach Resort - Where Tranquility Meets Coastal Luxury</h1>
                <p class="abt-desc">Escape to Harborview Resort and immerse yourself in a captivating coastal haven where breathtaking views, unparalleled luxury, and warm hospitality seamlessly blend together. Nestled along the pristine shores of a tranquil beach, our resort offers an idyllic retreat where relaxation and adventure intertwine. Indulge in the serenity of our beautifully appointed accommodations, each thoughtfully designed to provide the utmost comfort and style.</p>
              </div>
              <!-- end about text-->
          
            </div>
            <!-- end col -->
          </div>
    </section>
</body>
</html>