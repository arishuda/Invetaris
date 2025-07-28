<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>@yield('page-title')</title>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>E-Aset | Login</title>

    <link rel="stylesheet" href={{asset('sneat/assets/vendor/fonts/boxicons.css')}} />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('sneat/assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('sneat/assets/vendor/css/theme-default.css')}}" class={{asset('template-customizer-theme-css')}} />
    <link rel="stylesheet" href="{{asset('sneat/assets/css/demo.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{asset('sneat/assets/vendor/css/pages/page-auth.css')}}" />
    <!-- Helpers -->
    <script src="{{asset('sneat/assets/vendor/js/helpers.js')}}"></script>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    {{-- <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}

    <link rel="stylesheet" href="{{asset('vendor/izitoast/dist/css/iziToast.min.css') }}">

    <!-- Include script -->
    <script type="text/javascript">
        

        function callbackCatch(error){
            console.error('Error:', error)
        }
    </script>

    {{-- {!! htmlScriptTagJsApi([
        'callback_then' => 'callbackThen',
        'callback_catch' => 'callbackCatch',
    ]) !!} --}}
    <link rel="ico" href="{!! asset('image/datakom-logo.jpeg') !!}"/>
    @yield('content')
</head>
<body>
   
        </main>
    </div>

    <script src= "{{asset('sneat/assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{asset('sneat/assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('sneat/assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{asset('sneat/assets/vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{asset('sneat/assets/js/main.js')}}"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('sneat/assets/js/config.js')}}"></script>
    <script src="{{asset('vendor/izitoast/dist/js/iziToast.min.js') }}" 
    type="text/javascript"></script>

    <script type="text/javascript">

        @if (session()->has('success'))
            @if(is_array(session('success')))
                @foreach (session('success') as $success)
                    message = "{{ $success }}";
                @endforeach
            @else
                message = "{{ session('success') }}";
            @endif

            showToast('green', 'Success', message);
        @endif

        @if (session()->has('error'))
            @if(is_array(session('error')))
                @foreach (session('error') as $error)
                    message = "{{ $error }}";
                @endforeach
            @else
                message = "{{ session('error') }}";
            @endif

            showToast('red', 'Error', message);
        @endif

        @if (session()->has('warning'))
            @if(is_array(session('warning')))
                @foreach (session('warning') as $warning)
                    message = "{{ $warning }}";
                @endforeach
            @else
                message = "{{ session('warning') }}";
            @endif

            showToast('yellow', 'Warning', message);
        @endif

        @if (session()->has('info'))
            @if(is_array(session('info')))
                @foreach (session('info') as $info)
                    message = "{{ $info }}";
                @endforeach
            @else
                message = "{{ session('info') }}";
            @endif

            showToast('blue', 'Info', message);
        @endif

        function showToast(type, title, message, position = 'topRight') {
            iziToast.show({
                timeout: 5000,
                resetOnHover: true,
                transitionIn: 'flipInX',
                transitionOut: 'flipOutX',
                color: type, // blue, red, green, yellow
                position: position, // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
                title: title,
                message: message
            });
        }

        @if($errors->any())
            var times = {{ $int = (int) filter_var($errors->first(), FILTER_SANITIZE_NUMBER_INT) }};
            var timeOut = times*1000;
            let timerInterval;

            Swal.fire({
                icon: 'error',
                title: 'Too many login attempts!',
                html: 'Please try again in <b></b> seconds.',
                timer: timeOut,
                timerProgressBar: true,
                allowOutsideClick: false,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                },
                didOpen: () => {
                    Swal.showLoading()
                    const b = Swal.getHtmlContainer().querySelector('b')
                    timerInterval = setInterval(() => {
                        b.textContent = Math.round(Swal.getTimerLeft()/1000)
                    }, 1000)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
            }).then((result) => {
            /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log('I was closed by the timer')
                }
            })
        @endif

       




      </script>
</body>
</html>
