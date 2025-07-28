
@extends('layouts.app')
@section('content')

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('image/datakom-logo.jpeg') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

  
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="/" class="app-brand-link gap-2">
                  <div style="text-align: center;">
                    <img width="100" height="120" src="{{ asset('image/datakom-logo.jpeg') }}">
                    {{-- <b><h3>ASET PUSDATIN</h3></b> --}}
                     </div>
                  {{-- <span class="app-brand-text demo text-body fw-bolder">Sneat</span> --}}
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2">Welcome 👋</h4>
              <p class="mb-4">Please sign-in to your account</p>

              <form method="POST" action="{{ route('login') }}" id="FormData">
                {{ csrf_field() }}
                <div class="mb-3">
                  <label for="username" class="form-label">Email or Username</label>
                  <input
                  id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus  placeholder="Username"
                  />
                  @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                </div>
                <div class="mb-3 form-password-toggle">
                  {{-- <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                    <a href="auth-forgot-password-basic.html">
                      <small>Forgot Password?</small>
                    </a>
                  </div> --}}
                  <div class="input-group input-group-merge">
                    {{-- <label for="password" class="col-md-4 col-form-label text-md-left">{{ __('Password') }}</label> --}}
                    {{-- <div class="col-md-12"> --}}
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password" aria-describedby="password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  {{-- </div> --}}
                </div>
                <br>
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-23" style="padding-right: 0px !important; display: flex;">
                      <div class="captcha">
                          <span>{!! captcha_img('math') !!}</span>
                          <button type="button" class="btn btn-primary refresh" id="refresh"><i class="fa fa-refresh fa-spin"></i></button>
                      </div>
                  </div>
              </div>
              <br>
              <div class="form-group">
                  <div class="input-group">
                      <input id="captcha" type="text" class="form-control" name="captcha" placeholder="Captcha" required>
                      @error('captcha') 
                     <label for="" class="text-danger">{{$message}}</label>
                      @enderror
                  </div>
              </div>
              <br>
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                </div>
                  <div class ="mb-3">
                      <a class="btn btn-warning d-grid w-100" href="{{route('scan')}}">Scan Barcode</a>
                  </div>
              </form>

              {{-- <p class="text-center">
                <span>New on our platform?</span>
                <a href="auth-register-basic.html">
                  <span>Create an account</span>
                </a>
              </p> --}}
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

   
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript">
$(document).ready(function() {
  $('.refresh').click(function() {
      $.ajax({
          type: 'GET',
          url: '{{ route("refreshcaptcha") }}',
          success: function(data) {
              $(".captcha span").html(data.captcha); // Update captcha image
          },
          error: function() {
              alert('Failed to refresh captcha. Please try again.'); // Handle error if refresh fails
          }
      });
  });
});
</script>
@endsection