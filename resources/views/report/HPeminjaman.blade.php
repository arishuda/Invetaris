@extends('layouts.lteapp')

@section('title-page')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>Laporan Peminjaman Barang</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
      <li class="breadcrumb-item active">Laporan Peminjaman Barang</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="card card-secondary">
  <div class="card-header text-white">
    <!-- <h3 class="card-title">Title</h3> -->

    <nav class="nav nav-pills nav-justified">
      <a class="nav-item nav-link active text-white" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Peminjaman</a>
      <a class="nav-item nav-link text-white" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Log Barang Kembali </a>
    </nav>

  </div>
  <div class="card-body">
  <div class="tab-content" id="pills-tabContent">
      <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
    <form action="{{ route('historypm') }}" method="get">
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
            <a href="{{ route('exportpeminjamanH', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-success btn-sm" data-toggle="" data-target="">
                Export Excel
            </a>
            </center>
        </div>
    </form>
        <div class="table-responsive">
          <table class="table table-bordered" id="table-pinjam">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th>Tanggal Pinjam</th>
                <th>Jumlah Pinjam</th>
                <th>Diambil</th>
                <th>Keperluan</th>
                <th>User Input</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        @include('sweetalert::alert')
          @foreach ($queryresult as $data)
          <tr>
            <td align="center"></td>
            <td >{{$data->nama_barang}}</td>
            <td>{{$data->tanggal_pinjam}}</td>
            <td>{{$data->jumlah_pinjam}} {{$data->satuan}}</td>
            <td>{{$data->dipinjam}}</td>
            <td>{{$data->keperluan}}</td>
            <td>{{$data->NAMA}}</td>
            <td align="center">
              @if($data->jumlah_kembali==NULL)
                <a  class="btn btn-danger btn-xs " value="belum_kembali">Belum Kembali</a>
              @elseif($data->jumlah_pinjam!=\DB::table('logkembali')->where('id_peminjaman', $data->id)->sum('jumlah_kembali') )
              <a  class="btn btn-warning btn-xs" value="kembali_sebagian">Kembali Sebagian</a>
              @else 
                <a class="btn btn-success btn-xs" value="sudah_kembali">Sudah kembali</a>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
    </table>
        </div>

      </div>
      <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
      <form action="{{ route('historypm') }}" method="get">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-info text-white" id="basic-addon1">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                    </div>
                    <input type="date" class="form-control" id="start_date_1" name="start_date_1" placeholder="Start Date 1">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-info text-white" id="basic-addon1">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                    </div>
                    <input type="date" class="form-control" id="end_date_1" name="end_date_1" placeholder="End Date 1">
                </div>
            </div>
        </div>
        <div><center>
            <button id="filter" class="btn btn-outline-info btn-sm">Filter</button>
            <button id="reset" class="btn btn-outline-warning btn-sm">Reset</button>
            <a href="{{ route('exportlog', ['start_date_1' => request('start_date_1'), 'end_date_1' => request('end_date_1')]) }}" class="btn btn-success btn-sm" data-toggle="" data-target="">
                Export Excel
            </a>
            </center>
        </div>
    </form>
        <div class="table-responsive">
           <table class="table table-bordered" id="table-pinjam-2">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Nama Barang</th>
                      <th>Jumlah kembali</th>
                      <th>Tanggal Kembali</th>
                  </tr>
              </thead>
              <tbody>
                @foreach ($Bquery as $data)
                <tr>
                  <td align="center"></td>
                  <td >{{$data->nama_barang}}</td>
                  <td align="center">{{$data->jumlah_kembali}}</td>
                  <td align="center">{{$data->tanggal_kembali}}</td>
                </tr>
                @endforeach
              </tbody>
          </table>
        </div>

      </div>
  </div>


</div>
  <!-- /.card-body -->
  {{-- <div class="card-footer">
    Footer
  </div> --}}
  <!-- /.card-footer-->
</div>
<!-- /.card -->

@endsection


@push('scripts')
<script>
$(function() {
  $('.select2').select2();
});

$(function () {
    var t = $('#table-pinjam').DataTable( {
     
        "columnDefs":
        [
          // {
          //   width: "5%",
          //   searchable: false,
          //     // "orderable": false,
          //   targets: 0
          // },
          // { width: "25%", targets: 1 },
          // { width: "10%", targets: 2 },
          // { width: "10%", targets: 3 },
          // { width: "10%", targets: 4 },
          // { width: "20%", targets: 5 },
          // { width: "10%", targets: 6 },
          // { width: "10%", targets: 7 }
        ]
        // "order": [[ 1, 'asc' ]]
    } );

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

} );

$(function () {
    var t = $('#table-pinjam-2').DataTable( {
        "columnDefs":
        [
          // {
          //   width: "5%",
          //   searchable: false,
          //     // "orderable": false,
          //   targets: 0
          // },
          // { width: "25%", targets: 1 },
          // { width: "10%", targets: 2 },
          // { width: "10%", targets: 3 },
          // { width: "10%", targets: 4 },
          // { width: "10%", targets: 5 },
          // { width: "10%", targets: 6 },
          // { width: "10%", targets: 7 },
          // { width: "10%", targets: 8 }
        ]
        // "order": [[ 1, 'asc' ]]
    } );

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

} );

// $(function() {
//   $("#jenisinput").change(function() {
//     if ($("#0").is(":selected")) {
//       $("#barangbaru").hide();
//       $("#tambahbarang").hide();
//     } else if ($("#1").is(":selected")) {
//       $("#barangbaru").show();
//       $("#tambahbarang").hide();
//     } else {
//       $("#barangbaru").hide();
//       $("#tambahbarang").show();
//     }
//   }).trigger('change');
// });

$(function () {

  // Department Change
  $('#id_barang').change(function(){

    // Department id
    var id = $(this).val();

    // Empty the dropdown
    // $('#sel_emp').find('option').not(':first').remove();

    // AJAX request 
    $.ajax({
      url: 'barangkeluar/fetch/'+id,
      type: 'get',
      dataType: 'json',
      success: function(response){

        var satuan = response['data'][0].satuan;
        var sekarang = response['data'][0].jumlah_sekarang;

        // $("#linkWA").show();
        $("#satuan").attr("value", satuan);
        $("#sisastok").attr("value", sekarang);

        // var len = 0;
        // if(response['data'] != null){
        //   len = response['data'].length;
        // }

        // if(len > 0){
        //   // Read data and create <option >
        //   for(var i=0; i<len; i++){

        //     var satuan = response['data'][i].satuan;
        //     var sekarang = response['data'][i].jumlah_sekarang;

        //     // $("#linkWA").show();
        //     $("#satuan").attr("value", satuan);
        //     $("#sisastok").attr("value", sekarang);
            


        //     // var option = "<option value='"+id+"'>"+name+"</option>"; 

        //     // $("#sel_emp").append(option); 
        //   }
        // }

      }
    });
    
  });

});
</script>
@endpush
