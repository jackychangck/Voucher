@extends('frontend.layouts.main')

@section('main-container')

  </div>

  <!-- login section -->
  <section class="contact_section ">
    <div class="container-fluid">

      <div class="row">
        <div class="col-md-4 px-0">
          <div class="img-box ">
            <img src="{{ url('frontend/images/contact-img.jpg')}}" class="box_img" alt="about img">
          </div>
        </div>
        <div class="col-md-5 mx-auto">
          <div class="form_container">
            <div class="mt-5">
          </div>
            <div class="heading_container heading_center">
              <h2>Login Page</h2>
            </div>
            <form action="{{ route('login.post') }}" method="POST">
              @csrf
              <div class="form-row">
                <div class="form-group col">
                  <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" required autocomplete="username" placeholder="Username" />
                  @error('username')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col">
                  <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="password"  placeholder="Password" />
                  @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ message }}</strong>
                  </span>
                @enderror
                </div>
              </div>
              <a href="{{ url('/register') }}" style="margin-left: 10px">Do not have account? Click to Register.</a>
              <br />
              <br />
              <div class="btn_box">
                <button>
                  Login
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end login section -->

@endsection