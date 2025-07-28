@extends('layouts.lteapp')

@section('title-page')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>Laporan Barang Masuk</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
      <li class="breadcrumb-item active">Laporan Barang Masuk</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="card">
  
  <div class="table-responsive">
  <div class="card-body">
    <form action="{{ route('historybm') }}" method="get">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-info text-white" id="basic-addon1">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Start Date">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-info text-white" id="basic-addon1">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                        </div>
                        <input type="date" class="form-control" id="end_date" name="end_date" placeholder="End Date">
                    </div>
                </div>
            </div>
            <div><center>
                <button id="filter" class="btn btn-outline-info btn-sm">Filter</button>
                <button id="reset" class="btn btn-outline-warning btn-sm">Reset</button>
                <a href="{{ route('exportmasuk', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-success btn-sm" data-toggle="" data-target="">
                Export Excel
                </a>
                </center>
            </div>
        </form>
     <table class="table table-bordered" id="mod-masuk">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Kib</th>
                        <th>Serial Number</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>User Input</th>
                        <th>Last Update</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($results as $data)
                  <tr>
                    <td></td>
                    <td>{{$data->nama_barang}}</td>
                    <td>{{$data->kib}}</td>
                    <td>{{$data->serial_number}}</td>
                    <td>{{$data->tanggal_masuk}}</td>
                    <td>+ {{$data->jumlah_masuk}} {{$data->satuan}}</td>
                    <td>{{$data->NAMA}}</td>
                    <td>{{$data->last}}</td>
                  </tr>
                  @endforeach
                </tbody>
            </table>
  </div>
  <!-- /.card-body -->
  <div class="card-footer">
   
  </div>
  <!-- /.card-footer-->



  <!-- modal -->
  <!-- /.modal -->
</div>
<!-- /.card -->

@endsection


@push('scripts')
<script>
$(document).ready( function () {

  var mod1 = $('#mod-masuk').DataTable( {
      "columnDefs":
      [
        {
          // width: "5%",
          searchable: false,
            // "orderable": false,
          targets: 0
        }
        // { width: "40%", targets: 1 },
        // { width: "40%", targets: 2 }
      ]
      // "order": [[ 1, 'asc' ]]
  } );

  mod1.on( 'order.dt search.dt', function () {
      mod1.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      } );
  } ).draw();

});


</script>
@endpush
