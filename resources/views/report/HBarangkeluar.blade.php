@extends('layouts.lteapp')

@section('title-page')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>Laporan Barang Keluar</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
      <li class="breadcrumb-item active">Laporan Barang Keluar</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="card card-secondary">
  <div class="card-header text-white">
    <!-- <h3 class="card-title">Title</h3> -->

    <nav class="nav nav-pills nav-justified">
      <a class="nav-item nav-link active text-white" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Aset</a>
      <a class="nav-item nav-link text-white" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Barang Habis Pakai/Lainnya</a>
    </nav>

  </div>
  <div class="card-body">
  <div class="tab-content" id="pills-tabContent">
      <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
    <form action="{{ route('history') }}" method="get">
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
            <a href="{{ route('exportasetbarang', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-success btn-sm" data-toggle="" data-target="">
                Export Excel
            </a>
            </center>
        </div>
    </form>
        <div class="table-responsive">
          <table class="table table-bordered" id="table-Hbarangkeluar-1">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Nama Barang</th>
                      <th>TA</th>
                      <th>Jumlah keluar</th>
                      <th>Tanggal Keluar</th>
                      <th>diambil</th>
                      <th>Keperluan</th>
                      <th>Last Update</th>
                  </tr>
              </thead>
              <tbody>
                @foreach ($AqueryResults as $data)
                <tr>
                  <td align="center"></td>
                  <td >{{$data->nama_barang}}</td>
                  <td >{{$data->tahun_anggaran}}</td>
                  <td align="center">{{$data->jumlah_keluar}}</td>
                  <td align="center">{{$data->tanggal_keluar}}</td>
                  <td align="center">{{$data->diambil}}</td>
                  <td>{{$data->keperluan}}</td>
                  <td>{{$data->last}}</td>
                </tr>
                @endforeach
              </tbody>
          </table>
        </div>

      </div>
      <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
      <form action="{{ route('history') }}" method="get">
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
            <a href="{{ route('exportbaranghabis', ['start_date_1' => request('start_date_1'), 'end_date_1' => request('end_date_1')]) }}" class="btn btn-success btn-sm" data-toggle="" data-target="">
                Export Excel
            </a>
            </center>
        </div>
    </form>
        <div class="table-responsive">
          <table class="table table-bordered" id="table-Hbarangkeluar-2">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Nama Barang</th>
                      <th>TA</th>
                      <th>Jumlah keluar</th>
                      <th>Tanggal Keluar</th>
                      <th>diambil</th>
                      <th>Keperluan</th>
                      <th>Last Update</th>
                      <th>Act</th>
                  </tr>
              </thead>
              <tbody>
                @foreach ($BqueryResults->where('aktif', 1) as $data)
                <tr>
                  <td align="center"></td>
                  <td >{{$data->nama_barang}}</td>
                  <td >{{$data->tahun_anggaran}}</td>
                  <td align="center">{{$data->jumlah_keluar}}</td>
                  <td align="center">{{$data->tanggal_keluar}}</td>
                  <td align="center">{{$data->diambil}}</td>
                  <td>{{$data->keperluan}}</td>
                  <td>{{$data->last}}</td>
                  <td>
                    <a href="{{route('deleteHBK', $data->id)}}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="fa fa-trash"></i></a>
                  </td>
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
    var t = $('#table-Hbarangkeluar-1').DataTable( {
      "initComplete" : function () {
            this.api().columns([2]).every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.header()) )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
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
    var t = $('#table-Hbarangkeluar-2').DataTable( {
      "initComplete" : function () {
            this.api().columns([2]).every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.header()) )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
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
