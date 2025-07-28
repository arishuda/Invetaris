<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('addon/ionicons.min.css') }}">
    <!-- DataTables -->
    <link href="{{ asset('lte2/dist/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('lte2/dist/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugin/ion/css/ionicons.min.css') }}">


    <link rel="stylesheet" href="{{ asset('lte2/dist/vendor/themify-icons/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('lte2/dist/vendor/perfect-scrollbar/css/perfect-scrollbar.css') }}">
    {{-- <link rel="stylesheet" href="{{asset('lte2/dist/assets/css/style.min.css')}}"> --}}
    <link rel="stylesheet" href="{{ asset('lte2/dist/assets/css/bootstrap-override.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte2/dist/assets/css/dark.min.css') }}">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> --}}


    {{-- DataTables --}}
    <link rel="stylesheet" href="{{ asset('plugin/datepicker/css/bootstrap-datepicker.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('lte/plugins/fancybox/dist/jquery.fancybox.min.css') }}">
    {{-- <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/sweetalert2@10.10./dist/sweetalert2.min.css"> --}}

    <title>@yield('page-title')</title>
    <link rel="icon" href="{!! asset('image/datakom-logo.jpeg') !!}" />
    @stack('styles')

</head>


<body class="hold-transition sidebar-mini">

    @include('sweetalert::alert')
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link"> {{ Carbon\Carbon::now()->format('l, d F Y') }}</a>
                </li>
            </ul>


            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fa fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">



                        <a class="dropdown-item" href="{{ route('editpswd') }}">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Change Password
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>

            </ul>
        </nav>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin akan log out ?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Klik "Logout" jika anda ingin keluar.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            {{ csrf_field() }}
                            <button class="btn btn-dark" type="submit">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-4 sidebar-dark-primary">
            <!-- Brand Logo -->
            <a href="" class="brand-link">
                <img src="{{ asset('image/datakom-logo.jpeg') }}" class="brand-image">
                <span class="brand-text font-weight-light">E-Aset Pusdatin</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        @if (Auth::user()->gambar == '')
                            <img src="{{ asset('lte/dist/img/avatar5.png') }}" class="img-circle elevation-2"
                                alt="User Image">
                        @else
                            <!-- <img src="{{ asset('images/user/' . Auth::user()->gambar) }}" alt="profile image" width="150px" -->
                            <img src="{{ asset('image/images.png') }}" alt="profile image" width="150px"
                                class="img-circle elevation-2">
                        @endif

                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                        <div>
                            <center><small class="designation text-success"
                                    style="text-transform: uppercase;letter-spacing: 1px;">
                                    {{ Auth::user()->level }}
                                </small></center>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-flat" data-widget="treeview"
                        role="menu" data-accordion="false">


                        <li class="nav-item">
                            <a href="{{ route('home') }}"
                                class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-home"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>

                        @if (Auth::user()->level == 'superadmin')
                            <li class="nav-item has-treeview ">
                                <a href="#" class="nav-link  {{ setActive(['admin*', 'user*']) }}">
                                    <i class="nav-icon fa fa-users"></i>
                                    <p>
                                        Data User
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>

                                <ul class="nav nav-treeview ">
                                    <li class="nav-item ">
                                        <a class="nav-link {{ setActive(['admin*']) }}" href="{{ url('/admin') }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Data Admin</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ setActive(['user*']) }}"
                                            href="{{ route('user.index') }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Data Teknisi</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ route('domain') }}"
                                class="nav-link {{ request()->is('domain') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-cubes"></i>
                                <p>
                                    Domain
                                </p>
                            </a>


                        <li class="nav-item">
                            <a href="{{ route('stok') }}"
                                class="nav-link {{ request()->is('stok') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-database"></i>
                                <p>
                                    Stok Barang
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('barangmasuk') }}"
                                class="nav-link {{ request()->is('barangmasuk') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-arrow-circle-left"></i>
                                <p>
                                    Barang Masuk
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('barangrusak') }}"
                                class="nav-link {{ request()->is('barangrusak') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-times-circle"></i>
                                <p>
                                    Barang Rusak
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('barangkeluar') }}"
                                class="nav-link {{ request()->is('barangkeluar') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-arrow-circle-right"></i>
                                <p>
                                    Barang Keluar
                                </p>
                            </a>
                        </li>

                        <!-- <li class="nav-item">
                            <a href="#" class="nav-link {{ setActive(['keranjangbarangkeluar', 'barangkeluar']) }}">
                                <i class="nav-icon ion ion-social-dropbox"></i>
                                <p>
                                    Data Barang Keluar
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                <a href="{{ route('keranjangbarangkeluar') }}"
                                class="nav-link {{ request()->is('keranjangbarangkeluar') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-dolly"></i>
                                <p>
                                    Keranjang Barang Keluar
                                </p>
                                </a>
                                </li>
                                <li class="nav-item">
                                <a href="{{ route('barangkeluar') }}"
                                class="nav-link {{ request()->is('barangkeluar') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-arrow-circle-right"></i>
                                <p>
                                    Barang Keluar
                                </p>
                            </a>
                                </li>
                            </ul>
                        </li> -->
                        <li class="nav-item">
                            <a href="{{ route('peminjaman') }}"
                                class="nav-link {{ request()->is('peminjaman') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-arrows-alt-h"></i>
                                <p> Peminjaman</p>
                            </a>
                        </li>

                        <!-- <li class="nav-item">
                            <a href="#" class="nav-link {{ setActive(['keranjangpeminjaman', 'peminjaman']) }}">
                                <i class="nav-icon ion ion-social-dropbox"></i>
                                <p>
                                    Data Barang Peminjaman
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                <a href="{{ route('keranjangpeminjaman') }}"
                                class="nav-link {{ request()->is('keranjangpeminjaman') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-dolly"></i>
                                <p>
                                    Keranjang Peminjaman
                                </p>
                                </a>
                                </li>
                                <li class="nav-item">
                                <a href="{{ route('peminjaman') }}"
                                class="nav-link {{ request()->is('peminjaman') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-arrows-alt-h"></i>
                                <p>
                                    Peminjaman
                                </p>
                            </a>
                                </li>
                            </ul>
                        </li> -->
                        @if (Auth::user()->level == 'superadmin')
                            <li class="nav-item">
                                <a href="{{ route('Regions') }}"
                                    class="nav-link {{ request()->is('regions') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-globe"></i>
                                    <p>
                                        Regions
                                    </p>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item has-treeview ">
                            <a href="#"
                                class="nav-link  {{ setActive(['lap/barangkeluar', 'lap/barangmasuk', 'lap/barangrusak', 'lap/peminjaman']) }}">
                                <i class="nav-icon fa fa-clock"></i>
                                <p>
                                    Laporan
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>

                            <ul class="nav nav-treeview ">
                                <li class="nav-item ">
                                    <a
                                        class="nav-link {{ request()->is('lap/barangkeluar') ? 'active' : '' }} "href="{{ route('history') }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p> Laporan Barang Keluar</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ request()->is('lap/barangmasuk') ? 'active' : '' }} "href="{{ route('historybm') }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Laporan Barang Masuk</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ request()->is('lap/barangrusak') ? 'active' : '' }} "href="{{ route('historyrsk') }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Laporan Barang Rusak</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link {{ request()->is('lap/peminjaman') ? 'active' : '' }} "href="{{ route('historypm') }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Laporan Peminjaman Barang </p>
                                    </a>
                                </li>
                            </ul>
                        </li>



                        @if (Auth::user()->level == 'superadmin' || Auth::user()->level == 'admin')
                            <li class="nav-item">
                                <a href="{{ route('logaset') }}"
                                    class="nav-link {{ request()->is('log') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        Log Aset
                                    </p>
                                </a>
                            </li>
                        @endif
                        <!-- /.User -->
                        <!-- @if (Auth::user()->level == 'user')
<li class="nav-item">
                                <a href="{{ route('barangkeluar') }}"
                                class="nav-link {{ request()->is('barangkeluar') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-arrow-circle-right"></i>
                                <p>
                                    Barang Keluar
                                </p>
                            </a>
                                </li>

                        <li class="nav-item">
                                <a href="{{ route('peminjaman') }}"
                                class="nav-link {{ request()->is('peminjaman') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-arrows-alt-h"></i>
                                <p>
                                    Peminjaman
                                </p>
                            </a>
                        </li>
@endif -->
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">

                    @yield('title-page')

                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">

                @yield('content')

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 3.0.4
            </div>
            <strong>Copyright &copy; 2019 - {{ Carbon\Carbon::now()->format('Y') }} <a
                    href="/home">E-ASET</a>.</strong> All rights
            reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->

    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <!-- DataTables --> --}}
    <script src="{{ asset('lte2/src/js/pages/datatables.js') }}"></script>
    <script src="{{ asset('lte2/dist/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('lte2/dist/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('lte2/dist/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('lte2/dist/vendor/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('lte2/dist/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script src="{{ asset('js/sweetalert2.all.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('lte/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>

    <script src="{{ 'lte2/dist/vendor/bootstrap/js/bootstrap.bundle.js' }}"></script>
    <script src="{{ 'lte2/dist/vendor/perfect-scrollbar/perfect-scrollbar.min.js' }}"></script>

    {{-- datatble --}}
    <script type="text/javascript" src="{{ asset('plugin/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('vendor/izitoast/dist/js/iziToast.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('lte/plugins/fancybox/dist/jquery.fancybox.min.js') }}"></script>
    <script>
        @if (Session::has('success'))
            iziToast.success({
                message: "{{ session('success') }}",
                position: 'topRight'
            });
        @endif

        @if (Session::has('error'))
            iziToast.error({
                message: "{{ session('error') }}",
                position: 'topRight'
            });
        @endif

        @if (Session::has('info'))
            iziToast.info({
                message: "{{ session('info') }}",
                position: 'topRight'
            });
        @endif

        @if (Session::has('warning'))
            iziToast.warning({
                message: "{{ session('warning') }}",
                position: 'topRight'
            });
        @endif

        const styling = `
    color:green;
    background-color:white;
    border-left: 1px solid blue;
    padding: 8px;
    font-weight: 600;
    font-family: Courier;
`;

        console.log(
            `%c 🚀 Created By Aris Huda Prawira 🚀`,
            `${styling} font-size: 40px; border-top: 1px solid yellow;`
        );
    </script>


    @stack('scripts')
    @include('sweetalert::alert')
</body>

</html>
