@extends('layouts.lteapp')

@section('title-page')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>Barang Keluar</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
      <li class="breadcrumb-item active">Barang Keluar</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="card card-danger">
  <div class="card-header">
    <!-- <h3 class="card-title">Title</h3> -->

    <div class="card-tools">
      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lg">
        Post Barang Keluar
      </button>
      <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
        <i class="fas fa-minus"></i></button>
      <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
        <i class="fas fa-times"></i></button> -->
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="table-barangkeluar">
          <thead>
              <tr>
                  <th>#</th>
                  <th>Nama Barang</th>
                  <th>TA</th>
                  <th>Jumlah keluar</th>
                  <th>Tanggal Keluar</th>
                  <th>diambil</th>
                  <th>Keperluan</th>
                  <th>Aksi</th>
              </tr>
          </thead>
          <tbody>
            @foreach ($query as $data)
            <tr>
              <td align="center"></td>
              <td >{{$data->nama_barang}}</td>
              <td align="center">{{$data->tahun_anggaran}}</td>
              <td align="center">{{$data->jumlah_keluar}}</td>
              <td align="center">{{$data->tanggal_keluar}}</td>
              <td align="center">{{$data->diambil}}</td>
              <td>{{$data->keperluan}}</td>
              <td align="center">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                  <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#edit-{{$data->id}}" data-placement="bottom" title="Edit"><i class="fa fa-edit"></i></button>
				  
                  <a href="{{route('deletebarangkeluar', $data->id)}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Delete" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="fa fa-trash"></i></a>
                  
                  @if($data->buatba==0)
                  <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      BA
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <a class="dropdown-item" href="{{route('bast', $data->BA_ID)}}">BA Serah Terima</a>
                      <a class="dropdown-item" href="{{route('bapp', $data->BA_ID)}}">BA Pinjam Pakai</a>
                      <a class="dropdown-item" href="#" data-toggle="modal" data-target="#upload-{{$data->id}}" data-placement="bottom" title="Edit">Upload BA</a>
                      
                    </div>
                  </div>
                  @elseif($data->buatba==1) 
                  {{-- //SERAH TERIMA --}}
                  <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Aksi
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <a href="{{route('bast', $data->BA_ID)}}" class="dropdown-item">Lihat BA</a>
                      {{-- <a class="dropdown-item" href="#">Lihat BA</a> --}}
                      {{-- =-------------------------------- --}}
                      <hr>
                      @if($data->jumlah_kembali==NULL)
                        <a href="{{route('inputtd', $data->IDPEMINJAMAN)}}" class="dropdown-item">Belum Kembali</a>
                      @elseif($data->jumlah_pinjam!=\DB::table('logkembali')->where('id_peminjaman', $data->IDPEMINJAMAN)->sum('jumlah_kembali') )
                        <a href="{{route('inputtd', $data->IDPEMINJAMAN)}}" class="dropdown-item">Belum Kembali</a>
                      @else 
                        <a class="dropdown-item" href="#">Sudah Kembali</a>
                      @endif
                    </div>
                  </div>
                  @elseif($data->buatba==2)
                  {{-- PINJAM PAKAI --}}
                  <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Aksi
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <a class="dropdown-item" href="{{route('bapp', $data->BA_ID)}}">Lihat BA</a>
                      {{-- =-------------------------------- --}}
                      <hr>
                      @if($data->jumlah_kembali==NULL)
                        <a href="{{route('inputtd', $data->IDPEMINJAMAN)}}" class="dropdown-item">Belum Kembali</a>
                      @elseif($data->jumlah_pinjam!=\DB::table('logkembali')->where('id_peminjaman', $data->IDPEMINJAMAN)->sum('jumlah_kembali') )
                        <a href="{{route('inputtd', $data->IDPEMINJAMAN)}}" class="dropdown-item">Belum Kembali</a>
                      @else 
                        <a class="dropdown-item" href="#">Sudah Kembali</a>
                      @endif
                      {{-- <a class="dropdown-item" href="#">Kembalikan Barang</a> --}}
                    </div>
                  </div>
                  @elseif($data->buatba==3) 
                  {{-- //UPLOADAN --}}
                  <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Aksi
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      {{-- <a class="dropdown-item" href="#" data-toggle="modal" data-target="#BA-{{$data->id}}" data-placement="bottom" title="BA">Lihat BA</a> --}}
                      <a class="dropdown-item" href="{{url('uploadba/'.$data->NAMAFILE)}}" target="_blank">Lihat BA</a>
                      {{-- =-------------------------------- --}}
                      <hr>
                      @if($data->jumlah_kembali==NULL)
                        <a href="{{route('inputtd', $data->IDPEMINJAMAN)}}" class="dropdown-item">Belum Kembali</a>
                      @elseif($data->jumlah_pinjam!=\DB::table('logkembali')->where('id_peminjaman', $data->IDPEMINJAMAN)->sum('jumlah_kembali') )
                        <a href="{{route('inputtd', $data->IDPEMINJAMAN)}}" class="dropdown-item">Belum Kembali</a>
                      @else 
                        <a class="dropdown-item" href="#">Sudah Kembali</a>
                      @endif
                      
                        
                    </div>
                  </div>
                  @endif

                  
                  

                  {{-- <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      BA
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <a class="dropdown-item" href="{{route('ba', $data->BA_ID)}}">BA Serah Terima</a>
                      <a class="dropdown-item" href="#">BA Pinjam Pakai</a>
                      <a class="dropdown-item" href="#" data-toggle="modal" data-target="#upload-{{$data->id}}" data-placement="bottom" title="Edit">Upload BA</a>
                      <hr>
                      <a class="dropdown-item" href="#">Lihat BA</a>
                      <hr>
                      <a class="dropdown-item" href="#">Kembalikan Barang</a>
                    </div>
                  </div> --}}

                </div>

                {{-- @if($data->buatba==0)
                <a href="{{route('ba', $data->BA_ID)}}" class="btn btn-sm btn-primary">Buat BA</a>
                @else
                <a href="{{route('ba', $data->BA_ID)}}" class="btn btn-sm btn-success">Lihat BA</a>
                @endif --}}
                
                {{-- <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#edit-{{$data->id}}" data-placement="bottom" title="Edit"><i class="fa fa-edit"></i></button>
                <a href="{{route('deletebarangkeluar', $data->id)}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Delete" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="fa fa-trash"></i></a> --}}
              </td>
            </tr>

            <div class="modal fade" id="edit-{{$data->id}}">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Edit Barang Keluar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
  
  
                  <div class="modal-body">
  
  
                          <form action="{{route('updatebarangkeluar', $data->id)}}" method="post">
                            @csrf
                            
                            <input type="hidden" name="id_barang" value="{{$data->id_barang}}">

                            <div class="form-group">
                              <label>Nama Barang</label>
                              <input type="text" class="form-control" name="nama_barang" value="{{$data->nama_barang}}" readonly>
                            </div>
  
                            <div class="row">
                              <div class="col-sm-6">
                                <div class="form-group">
                                  <label>Jumlah Barang Keluar</label>
                                  <input type="text" class="form-control" name="jumlah_keluar" value="{{$data->jumlah_keluar}} {{$data->satuan}}" readonly>
                                </div>
                              </div>
                              <div class="col-sm-6">
                                <div class="form-group">
                                  <label>Tanggal Keluar</label>
                                  <input type="text" class="form-control" name="tanggal_keluar" value="{{$data->tanggal_keluar}}" readonly>
                                </div>
                              </div>
                            </div>
  
                            <div class="form-group">
                              <label>Di ambil oleh</label>
                              <input type="text" class="form-control" name="diambil" value="{{$data->diambil}}" readonly>
                            </div>
  
                            <div class="form-group">
                              <label>Keperluan</label>
                              <input type="text" class="form-control" name="keperluan" value="{{$data->keperluan}}">
                            </div>
  
  
                            <button type="submit" name="button" class="btn btn-success">Update</button>
                          </form>
  
  
  
                  </div>
                  <!-- <div class="modal-footer justify-content-between"> -->
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                  <!-- </div> -->
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            {{-- -------------------------------------------------------------------------------- --}}

            <div class="modal fade" id="upload-{{$data->id}}">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Upload Berita Acara</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
  
  
                  <div class="modal-body">
  
  
                          <form action="{{route('uploadBA')}}" method="post" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="id_barangkeluar" value="{{$data->id}}">
                            
                            <input type="hidden" name="id_barang" value="{{$data->id_barang}}">

                            <div class="form-group">
                              <label>Upload File</label>
                              <input type="file" class="form-control" name="dokumen">
                            </div>

                            {{-- <div class="input-group mb-3">
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile02" name="filename">
                                <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                              </div>
                              {{-- <div class="input-group-append">
                                <span class="input-group-text" id="">Upload</span>
                              </div> 
                            </div> --}}
  
                            {{-- <div class="row">
                              <div class="col-sm-6">
                                <div class="form-group">
                                  <label>Jumlah Barang Keluar</label>
                                  <input type="text" class="form-control" name="jumlah_keluar" value="{{$data->jumlah_keluar}} {{$data->satuan}}" readonly>
                                </div>
                              </div>
                              <div class="col-sm-6">
                                <div class="form-group">
                                  <label>Tanggal Keluar</label>
                                  <input type="text" class="form-control" name="tanggal_keluar" value="{{$data->tanggal_keluar}}" readonly>
                                </div>
                              </div>
                            </div>
  
                            <div class="form-group">
                              <label>Di ambil oleh</label>
                              <input type="text" class="form-control" name="diambil" value="{{$data->diambil}}" readonly>
                            </div>
  
                            <div class="form-group">
                              <label>Keperluan</label>
                              <input type="text" class="form-control" name="keperluan" value="{{$data->keperluan}}">
                            </div> --}}
  
  
                            <button type="submit" name="button" class="btn btn-success">Upload</button>
                          </form>
  
  
  
                  </div>
                  <!-- <div class="modal-footer justify-content-between"> -->
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                  <!-- </div> -->
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            {{-- -------------------------------------------------------------------------------- --}}

            <div class="modal fade" id="BA-{{$data->id}}">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Berita Acara {{$data->nama_barang}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
  
  
                  <div class="modal-body">
                      <iframe src="{{url('uploadba/'.$data->NAMAFILE)}}" frameborder="0"></iframe>
                  </div>
                  <!-- <div class="modal-footer justify-content-between"> -->
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                  <!-- </div> -->
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            @endforeach
          </tbody>
      </table>
    </div>
  </div>
  <!-- /.card-body -->
  <div class="card-footer">
    Footer
  </div>
  <!-- /.card-footer-->



  <!-- modal -->
  <div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Form Barang Keluar</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>


        <div class="modal-body">

              <div id="tambahbarang">
                <form action="{{route('kurangBK')}}" method="post">
                  @csrf


                  <div class="form-group">
                    <label>Pilih Barang</label>
                    <select class="select2 form-control" name='id_barang' id='id_barang'>
                      <option value="">- Pilih -</option>
                      @foreach ($select['data'] as $data)
                      <option value="{{$data->id}}">{{$data->nama_barang}}</option>
                      @endforeach
                    </select>
                  </div>
                  

                  <div class="row">
                    
                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Tahun Anggaran</label>
                        <input type="text" class="form-control" id="tahun" readonly>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Stok Sekarang</label>
                        <input type="text" class="form-control" id="sisastok" readonly>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Satuan</label>
                        <input type="text" class="form-control" name="satuan" id="satuan" readonly>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Jumlah Barang Keluar</label>
                        <input type="text" class="form-control" name="jumlah_keluar">
                      </div>
                    </div>

                  </div>

                  <div class="form-group">
                    <label>Tanggal Keluar</label>
                    <input class="form-control" id="date-bk" name="tanggal_keluar" placeholder="yyyy-mm-dd" type="text">
                    {{-- <input type="date" class="form-control" name="tanggal_keluar"> --}}
                  </div>

                  <div class="form-group">
                    <label>di Ambil Oleh</label>
                    <input type="text" class="form-control" name="diambil">
                  </div>

                  <div class="form-group">
                    <label>Keperluan</label>
                    <textarea name="keperluan" rows="3" cols="80" class="form-control"></textarea>
                    <!-- <input type="text" class="form-control" name="diambil"> -->
                  </div>

                  <button type="submit" name="button" class="btn btn-primary">Input</button>
                </form>

              </div>

        </div>
        <!-- <div class="modal-footer justify-content-between"> -->
          <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        <!-- </div> -->
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
</div>
<!-- /.card -->

@endsection


@push('scripts')
<script>
$(function() {
  $('.select2').select2();
});

$(function () {
    var t = $('#table-barangkeluar').DataTable( {
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
          { width: "5%", targets: 0, searchable: false },
              // "orderable": false,  
          { width: "25%", targets: 1 },
          { width: "10%", targets: 2 },
          { width: "10%", targets: 3 },
          { width: "10%", targets: 4 },
          { width: "20%", targets: 5 },
          { width: "10%", targets: 6 },
          { width: "10%", targets: 7 }
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
        var tahun = response['data'][0].tahun_anggaran;

        // $("#linkWA").show();
        $("#satuan").attr("value", satuan);
        $("#sisastok").attr("value", sekarang);
        $("#tahun").attr("value", tahun);



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

$(function () {
  $("#date-bk").datepicker({
        autoclose: true,
        todayHighlight: true,
        format : 'yyyy-mm-dd'
  });
});
</script>
@endpush
