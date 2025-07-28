@extends('layouts.lteapp')

@section('title-page')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>Detail Barang {{$data->nama_barang}}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/stok">Stok Barang</a></li>
      <li class="breadcrumb-item active">Detail Barang</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="card card-default">
  <div class="card-header">
    <!-- <h3 class="card-title">Data Stok Barang</h3> -->
    <div class="card-tools">
      {{-- <a href="/update/{{$data->id}}" class="btn btn-success">Edit</a> --}}
      <a href="{{route('stok')}}" class="btn btn-warning">Kembali</a>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Detail Barang</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
              </button>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">

            <a class="badge badge-primary" data-fancybox   style="margin-right: 10px;" width="40px" href="{{ url('stokbarang/'.$data->image) }}" >
                <i class="fa fa-eye"></i>
                View Image
            </a>

            <br><br>

            <div class="form-group">
              <label>Tahun Anggaran</label>
              <input type="text" class="form-control" name="nama_barang" value="{{$data->tahun_anggaran}}" readonly>
            </div>

            <div class="form-group">
              <label>Jenis Barang</label>
              <input type="text" class="form-control" name="nama_barang" value="@if($data->jenisbarang=="1") Aset @else Barang Habis Pakai @endif" readonly>
            </div>

            <div class="form-group">
              <label>Nama Barang</label>
              <input type="text" class="form-control" name="nama_barang" value="{{$data->nama_barang}}">
            </div>

            <div class="form-group">
              <label>KIB</label>
              <input type="text" class="form-control" name="kib" value="{{$data->kib}}" readonly>
            </div>

            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                  <label>Jumlah Barang Sekarang</label>
                  <input type="text" class="form-control" name="jumlah_sekarang" value="{{$data->jumlah_sekarang}} {{$data->satuan}}" readonly>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label>Jumlah Stok Awal</label>
                  <input type="text" class="form-control" name="jumlah_sekarang" value="{{$data->jumlah_awal}} {{$data->satuan}}" readonly>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label>Tanggal Update Stok</label>
                  <input type="text" class="form-control" name="stokupdate" value="{{$data->stokupdate}}" readonly>
                </div>
              </div>
              <!-- <div class="col-sm-6">
                <div class="form-group">
                  <label>History</label><br>
                  <button type="button" class="btn btn-block btn-secondary" data-toggle="modal" data-target="#history-{{$data->id}}"><i class="fa fa-history"></i></button>
                </div>
              </div> -->
            </div>

            <!-- <div class="form-group">
              <label>Tanggal Update Stok</label>
              <input type="text" class="form-control" name="stokupdate" value="{{$data->stokupdate}}" readonly>
            </div> -->

            <div class="form-group">
              <label>Lokasi Barang</label>
              <input type="text" class="form-control" name="lokasi" value="{{$data->lokasi}}">
            </div>


          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->

      <!-- --------------------------------------------------------- -->

      <div class="col-md-12">
        <div class="card card-primary collapsed-card">
          <div class="card-header">
            <h3 class="card-title">Barang Masuk</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
              </button>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">

            <table class="table table-responsive-lg" id="mod-masuk" style="width: 100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Serial Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($masuk as $data)
                  <tr>
                    <td></td>
                    <td>{{$data->tanggal_masuk}}</td>
                    <td>+ {{$data->jumlah_masuk}} {{$data->satuan}}</td>
                    <td>{{$data->serial_number}}</td>
                    <td><center><a href="{{route('qrcode',\Hashids::encode($data->id))}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Qrcode">
                    <i class="fa fa-qrcode"></i></a></td>
                  </tr>
                  @endforeach
                </tbody>
            </table>

          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->

      <!-- --------------------------------------------------------- -->

      <div class="col-md-12">
        <div class="card card-danger collapsed-card">
          <div class="card-header">
            <h3 class="card-title">Barang Keluar</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
              </button>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">

            <table class="table table-responsive-lg" id="mod-keluar" style="width:100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($keluar as $data)
                  <tr>
                    <td></td>
                    <td>{{$data->tanggal_keluar}}</td>
                    <td>- {{$data->jumlah_keluar}} {{$data->satuan}}</td>
                  </tr>
                  @endforeach
                </tbody>
            </table>

          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->

      <!-- --------------------------------------------------------- -->

      <div class="col-md-12">
        <div class="card card-success collapsed-card">
          <div class="card-header">
            <h3 class="card-title">Peminjaman Barang</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
              </button>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">

            <table class="table table-responsive-lg" id="mod-pinjam" style="width:100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <!-- <th>Nama Barang</th> -->
                        <th>Tanggal Pinjam</th>
                        <th>Jumlah Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Jumlah Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($pinjam as $data)
                  <tr>
                    <td></td>
                    <!-- <td>{{$data->nama_barang}}</td> -->
                    <td>{{$data->tanggal_pinjam}}</td>
                    <td>{{$data->jumlah_pinjam}} {{$data->satuan}}</td>
                    <td>
                      @if($data->jumlah_kembali==NULL)
                      -
                      @else
                      {{$data->tanggal_kembali}}
                      @endif
                    </td>
                    <td>
                      @if($data->jumlah_kembali==NULL)
                      -
                      @else
                      {{ \DB::table('logkembali')->where('id_peminjaman', $data->id)->sum('jumlah_kembali') }} {{$data->satuan}}
                      @endif
                    </td>
                    <td align="center">
                      @if($data->jumlah_kembali==NULL)
                        <span href="{{route('inputtd', $data->id)}}" class="badge badge-danger">Belum Kembali</span>
                        <!-- <span class="badge badge-danger">belum kembali</span> -->
                      @elseif($data->jumlah_pinjam!=\DB::table('logkembali')->where('id_peminjaman', $data->id)->sum('jumlah_kembali') )
                        <span href="{{route('inputtd', $data->id)}}" class="badge badge-warning">Kembali Sebagian</span>
                      @else
                        <span class="badge badge-success">Sudah kembali</span>
                      @endif

                    </td>
                  </tr>
                  @endforeach
                </tbody>
            </table>

          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
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

  //------------------------------------------------------------------

  var mod2 = $('#mod-keluar').DataTable( {
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

  mod2.on( 'order.dt search.dt', function () {
      mod2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      } );
  } ).draw();

  //------------------------------------------------------------------

  var mod3 = $('#mod-pinjam').DataTable( {
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

  mod3.on( 'order.dt search.dt', function () {
      mod3.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
      } );
  } ).draw();

  //------------------------------------------------------------------

  $('[data-fancybox]').fancybox({
        toolbar  : false,
        innerWidth: "40px",
        iframe : {
            preload : false,
            css : {
              width : '755px',}
        }
    })

} );
</script>


@endpush

@push('styles')

@endpush
