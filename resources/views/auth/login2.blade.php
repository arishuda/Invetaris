@extends('layouts.app')

@section('content') 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<section style="background-color: rgb(223, 221, 221)">
    <div class="container py-5 min-vh-100">
      <div class="row d-flex justify-content-center align-items-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5 ">
          <div class="card shadow-2-strong box-shadow " style="border-radius: 1rem;">
            <div  class="card-body p-5">
          <form method="POST" action="{{ route('login') }}" id="FormData">
            {{ csrf_field() }}
          <div style="text-align: center;">
           <img width="100" height="120" src="{{ asset('image/datakom-logo.jpeg') }}">
            </div>
          <div class="col-md-12" style="margin-bottom: 50px;">
                  <h2 style="text-align: center;">E-ASET PUSDATIN</h2>
                </div>
                
            
            <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-left">Username</label>

                            <!-- MODIFIKASI BAGIAN INI MENJADI FIELD USERNAME -->
                            <div class="col-md-12">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus  placeholder="Username">

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-left">{{ __('Password') }}</label>
                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-23" style="padding-right: 0px !important; display: flex;">
                                <div class="captcha">
                                    <span>{!! captcha_img('math') !!}</span>
                                    <button type="button" class="btn btn-primary refresh"><i class="fa fa-refresh fa-spin"></i></button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="input-group">
                                <input id="captcha" type="text" class="form-control" name="captcha" placeholder="Captcha" required>
                                @error('captcha') 
                            <label for="" class="text-danger">{{$message}}</label>
                                @enderror
                            </div>
                        </div>

            <!-- Checkbox -->
            <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="form-group ">
                            <div>
                                <a class="btn btn-warning btn-lg btn-block" href="{{route('scan')}}">Scan Barcode</a>
                            </div>
                        </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </form>
  
</section>


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
