<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="shortcut icon" href="{{ url('frontend/images/fevicon.png')}}" type="image/x-icon">
  <title>Fictionale</title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="{{ url('frontend/css/bootstrap.css') }}" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <!-- nice select -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
  <!-- font awesome style -->
  <link href="{{ url('frontend/css/font-awesome.min.css')}}" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="{{ url('frontend/css/style.css')}}" rel="stylesheet" />
  <!-- responsive style -->
  <link href="{{ url('frontend/css/responsive.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="https://common.olemiss.edu/_js/sweet-alert/sweet-alert.css">

</head>

<body>
  @include('sweetalert::alert')
    <!-- header section strats -->
    <header class="header_section">
      <div class="header_bottom" style="background-color:black">
        <div class="container-fluid">
          <nav class="navbar navbar-expand-lg custom_nav-container ">
            <a class="navbar-brand " href="{{ url('/') }}"> Fictionale </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class=""> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent" >
              <ul class="navbar-nav  " >
                @auth
                  <li class="nav-item active">
                    <a class="nav-link" href="{{ url('/home') }}">Home <span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/voucher') }}"> My Voucher</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile') }}">{{ auth()->user()->username }}</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <span>
                          Logout
                        </span>
                    </a>
                  </li>
                @else
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <span>
                            Login
                        </span>
                    </a>
                  </li>
                @endauth
              </ul>
            </div>
          </nav>

        </div>
      </div>
    </header>
    <!-- end header section -->
    @if($errors->any())
    <div class="col-12">
        @foreach($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach
    </div>
@endif

@if(session()->has('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    <?php echo "<script>setTimeout(function() { $('.alert').fadeOut(1500); }, 3000)</script>" ?>
@endif

@if(session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    <?php echo "<script>setTimeout(function() { $('.alert').fadeOut(1500); }, 3000)</script>" ?>
@endif
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
