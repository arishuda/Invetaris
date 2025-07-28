@extends('layouts.lteapp')

@section('title-page')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">

        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ \DB::table('stokbarang')->where('aktif', 1)->count() }}</h3>

                    <p>Stok Barang</p>
                </div>
                <div class="icon">
                    <i class="ion ion-cube"></i>
                </div>
                <a href="{{ route('stok') }}" class="small-box-footer">More info <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $barangKeluar }}</h3>
                    <p>Barang Keluar</p>
                </div>
                <div class="icon">
                    <i class="ion ion-log-out"></i>
                </div>
                <a href="{{ route('barangkeluar') }}" class="small-box-footer">More info <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ \DB::table('peminjaman')->where('aktif', 1)->count() }}</h3>

                    <p>Barang Dipinjam</p>
                </div>
                <div class="icon">
                    <i class="ion ion-arrow-swap"></i>
                </div>
                <a href="{{ route('peminjaman') }}" class="small-box-footer">More info <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stokbarang }}</h3>

                    <p>Barang Aset</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                {{-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
            </div>
        </div>
        
        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $BHS }}</h3>

                    <p>Barang Habis Pakai</p>
                </div>
                <div class="icon">
                    <i class="ion ion-social-dropbox"></i>
                </div>
                {{-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
            </div>
        </div>
        <!-- ./col -->
        <!-- ./col -->

    
    <!-- /.row -->
    
       
    <div class="col-lg-4 col-6">
        <div class="small-box bg-white">
            <div class="inner">

                <h3>{{ $BRS }}</h3>
                <p>Barang Rusak</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-dark">
            <div class="inner">

                <h3>{{ $STB }}</h3>
                <p> Total Barang</p>
            </div>
        </div>
    </div>
        
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">

                    <h3>{{ $BP }}</h3>
                    <p> Barang Pinjaman</p>
                </div>
            </div>
        </div>
        @if (Auth::user()->level == 'superadmin' || Auth::user()->level == 'admin')
            <div class="col-lg-4 col-6">
                <!-- small box -->
        @endif
        
    </div>
    




    <!-- /.card-body -->

    <!-- /.card-footer-->
    </div>
    @if (Auth::user()->level == 'admin' || Auth::user()->level =='superadmin')
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">Bar Chart</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="chart">
                <canvas id="barChart"
                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
    @endif
    <!-- /.card -->
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('barangmasuk.get_barang') }}",
                method: 'GET',
                serverside: true,
                success: function(response) {
                    var dataMap = {};

                    response.data.forEach(function(item) {
                        if(item.aktif == 1) {
                        if (dataMap[item.nama]) {
                            dataMap[item.nama].jumlah_masuk += item.jumlah_masuk;
                        } else {
                            dataMap[item.nama] = {
                                jumlah_masuk: item.jumlah_masuk,
                                nama: item.nama
                            };
                        }
                    }
                    });

                    var labels = Object.keys(dataMap);
                    var data = Object.values(dataMap).map(item => item.jumlah_masuk);

                    var barChartCanvas = $('#barChart').get(0).getContext('2d');
                    var barChartData = {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Masuk',
                            backgroundColor: 'rgba(60,141,188,0.9)',
                            borderColor: 'rgba(60,141,188,0.8)',
                            pointRadius: false,
                            pointColor: '#3b8bba',
                            pointStrokeColor: 'rgba(60,141,188,1)',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(60,141,188,1)',
                            data: data
                        }]
                    };

                    var barChartOptions = {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: {
                                ticks: {
                                    beginAtZero: true,
                                    precision: 0
                                }
                            },
                            x: {
                                ticks: {
                                    autoSkip: false
                                }
                            }
                        }
                    };

                    new Chart(barChartCanvas, {
                        type: 'bar',
                        data: barChartData,
                        options: barChartOptions
                    });
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    </script>
@endsection
