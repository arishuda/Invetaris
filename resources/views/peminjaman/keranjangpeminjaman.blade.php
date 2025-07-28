@extends('layouts.lteapp')

@section('title-page')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>Peminjaman Barang</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
      <li class="breadcrumb-item active">Keranjang Peminjaman Barang</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="card card-dark">
  <div class="card-header">
    <!-- <h3 class="card-title">Title</h3> -->
  
      &nbsp;
      
    <div class="card-tools">
      
      <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
        <i class="fas fa-minus"></i></button>
      <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
        <i class="fas fa-times"></i></button> -->
    </div>
  </div>
  <div class="card-body">
    <table class="table table-bordered" id="table-pinjam">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th>Tanggal Pinjam</th>
                <th>Jumlah Pinjam</th>
                <th>Diambil</th>
                <th>Keperluan</th>
                <th>Aksi</th>
                <!-- <th>Created At</th>
                <th>Updated At</th> -->
            </tr>
        </thead>
        <tbody>
        @include('sweetalert::alert')
          @foreach ($query as $data)
          <tr>
            <td align="center"></td>
            <td >{{$data->nama_barang}}</td>
            <td>{{$data->tanggal_pinjam}}</td>
            <td>{{$data->jumlah_pinjam}} {{$data->satuan}}</td>
            <td>{{$data->dipinjam}}</td>
           
            <td>{{$data->keperluan}}</td>
           
          <td>
            <a href="{{ route('editkeranjangpeminjaman', $data->id) }}" type="button" class="btn btn-sm btn-success" data-toggle="tooltip"  data-placement="bottom" title="Edit"><i class="fa fa-edit"></i></button></a>
            <a href="{{ route('deletepeminjaman', $data->id) }}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="fa fa-trash"></i></a>

            @if($data->nomor==NULL)
            <button type="submit" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="bottom">
              <i class="fa fa-times"></i>
            </button>
            @else
            <form action="{{ route('updatekeranjangp', $data->id) }}" method="post">
              @csrf <!-- Add CSRF token field for form protection -->
              <button type="submit" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom">
                <i class="fa fa-check"></i>
              </button>
            </form>
            @endif
            </td>
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
  <div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Form Peminjaman Barang</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>


        <div class="modal-body">


                <form action="{{route('inputpinjam')}}" method="post">
                  @csrf

                  <div class="form-group">
                    <label>Pilih Barang</label>
                    <select class="select2 form-control" name='id_barang' id='id_barang'>
                      <option value="">- Pilih -</option>
                      @foreach ($select as $data)
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

                  <!-- <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label>Satuan</label>
                        <select class="form-control" name="satuan">
                          <option value="">- Pilih -</option>
                          <option value="Unit">Unit</option>
                          <option value="Roll">Roll</option>
                        </select>
                      </div>
                    </div> -->

                    
                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Jumlah Barang Dipinjam</label>
                        <input type="text" class="form-control" name="jumlah_pinjam">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>di Ambil Oleh</label>
                    <input type="text" class="form-control" name="dipinjam">
                  </div>

                  <div class="form-group">
                    <label>Keperluan</label>
                    <textarea name="keperluan" rows="3" cols="80" class="form-control"></textarea>
                    <!-- <input type="text" class="form-control" name="diambil"> -->
                  </div>

                  <button type="submit" name="button" class="btn btn-primary">Input</button>
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
</div>
<!-- /.card -->

@endsection


@push('scripts')
<script>
$(function() {
  $('.select2').select2();
});

$(function () {
  $('#id_barang').change(function(){
    // Department id
    var id = $(this).val();
    // AJAX request 
    $.ajax({
      url: 'peminjaman/fetch/'+id,
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

      }
    });
    
  });

});

$(function () {
    var t = $('#table-pinjam').DataTable( {

      
        "columnDefs":
        [
          {
            // width: "5%",
            searchable: false,
              // "orderable": false,
            targets: 0
          }
          // { width: "45%", targets: 1 },
          // { width: "15%", targets: 2 },
          // { width: "20%", targets: 3 }
        ]
        // "order": [[ 1, 'asc' ]]
    } );

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

} );



</script>
@endpush
