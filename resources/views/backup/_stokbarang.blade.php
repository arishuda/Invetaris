@extends('layouts.lteapp')

@section('title-page')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>Stok Barang</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
      <li class="breadcrumb-item active">Stok Barang</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="card card-secondary">
  <div class="card-header">
    <!-- <h3 class="card-title">Data Stok Barang</h3> -->
    <div class="card-tools">
      <a href="{{ route('barangmasuk') }}" class="btn btn-primary">Barang Masuk</a> &nbsp;
      <a href="{{ route('barangkeluar') }}" class="btn btn-danger">Barang Keluar</a> &nbsp;
      <a href="{{ route('peminjaman') }}" class="btn btn-success">Peminjaman Barang</a>
      <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
        <i class="fas fa-minus"></i></button>
      <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
        <i class="fas fa-times"></i></button> -->
    </div>
  </div>
  <div class="card-body">

    <table class="table table-bordered" id="table-stok">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th>Tahun Pengadaan</th>
                <th>Stok Sekarang</th>
                <th>Stok Awal</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
          @foreach ($query as $data)
          <tr>
            <td align="center"></td>
            <!-- data-toggle="modal" data-target="#view-{{$data->id}}" -->
            <td><a href="{{ route('detailstok', $data->id) }}">{{$data->nama_barang}}</a></td>
            <td align="center">{{$data->tahun_anggaran}}</td>
            <td align="center">{{$data->jumlah_sekarang}}</td>
            <td align="center">{{$data->jumlah_awal}}</td>
            <td>{{$data->lokasi}}</td>
            <td align="center">
              <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#edit-{{$data->id}}" data-placement="bottom" title="Edit"><i class="fa fa-edit"></i></button>
              <a href="{{ route('deletestok', $data->id) }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Delete" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="fa fa-trash"></i></a>
              <!-- <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal tooltip" data-target="#delete" data-placement="bottom" title="Delete"><i class="fa fa-trash"></i></button> -->
              <!-- <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal tooltip" data-target="#modal" data-placement="bottom" title="Update"><i class="fa fa-history"></i></button> -->
              <!-- <a href="#" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-edit"></i></a> -->
              <!-- <a href="#" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Update"><i class="fa fa-history"></i></a> -->
            </td>
          </tr>

          <div class="modal fade" id="edit-{{$data->id}}">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Edit Stok Barang</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>


                <div class="modal-body">


                        <form action="{{ route('updatestok', $data->id) }}" method="post">
                          @csrf

                          <div class="form-group">
                            <label>Tahun Anggaran</label>
                            <select class="form-control" name="tahun">
                              <option value="{{$data->tahun_anggaran}}">{{$data->tahun_anggaran}}</option>
                              <option value="">-------</option>
                              @for($year=2014; $year <= date('Y'); $year++)
                                <option value="{{$year}}">{{$year}}</option>
                              @endfor
                            </select>
                          </div>

                          <div class="form-group">
                            <label>Jenis Barang</label>
                            <select class="form-control" name="jenisbarang">
                              <option value="{{$data->jenisbarang}}">@if($data->jenisbarang=="1") Aset @else Barang Habis Pakai @endif</option>
                              <option value="">-------</option>
                              <option value="1">Aset</option>
                              <option value="2">Barang Habis Pakai</option>
                            </select>
                          </div>

                          <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" class="form-control" name="nama_barang" value="{{$data->nama_barang}}">
                          </div>

                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Jumlah Barang Sekarang</label>
                                <input type="text" class="form-control" name="jumlah_sekarang" value="{{$data->jumlah_sekarang}} {{$data->satuan}}" readonly>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Jumlah Stok Awal</label>
                                <input type="text" class="form-control" name="jumlah_sekarang" value="{{$data->jumlah_awal}} {{$data->satuan}}" readonly>
                              </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <label>Tanggal Update Stok</label>
                            <input type="text" class="form-control" name="stokupdate" value="{{$data->stokupdate}}" readonly>
                          </div>

                          <div class="form-group">
                            <label>Lokasi Barang</label>
                            <input type="text" class="form-control" name="lokasi" value="{{$data->lokasi}}">
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

          <!-- ---------------------------------------------------------------------------- -->

          @endforeach
        </tbody>
    </table>

  </div>
  <!-- /.card-body -->
  <!-- <div class="card-footer">
    Footer
  </div> -->
  <!-- /.card-footer-->
</div>
<!-- /.card -->
@endsection

@push('scripts')
<script>
// $(function() {
//     $('#stok-table').DataTable({
//         processing: true,
//         serverSide: true,
//         // ajax: 'stok/json',
//         // columns: [
//         //     //{ data="",name:"test" },
//         //     { data: 'nama_barang', name: 'nama_barang' },
//         //     { data: 'jumlah_awal', name: 'jumlah_awal' },
//         //     { data: 'lokasi', name: 'lokasi' }
//         //     // { data: 'created_at', name: 'created_at' },
//         //     // { data: 'updated_at', name: 'updated_at' }
//         // ],
//         // columnDefs: [
//         //   // {
//         //   //   // data: "",
//         //   //   searchable: false,
//         //   //   orderable: false,
//         //   //   targets: 0
//         //   //  },
//         //    { width: "50%", targets: 0, render: function ( data, type, row, meta ) { return '<a href="/barang/"'+data+'"">'+data+'</a>'; } },
//         //    { width: "20%", targets: 1 },
//         //    { width: "30%", targets: 2 }
//         // ]
//     });
//
//     // t.on( 'order.dt search.dt', function () {
//     //     t.column(0, {}).nodes().each( function (cell, i) {
//     //         cell.innerHTML = i+1;
//     //     } );
//     // } ).draw();
// });
$(document).ready( function () {
    var t = $('#table-stok').DataTable( {
        "columnDefs":
        [
          {
            width: "5%",
            searchable: false,
              // "orderable": false,
            targets: 0
          },
          { width: "25%", targets: 1 },
          { width: "10%", targets: 2 },
          { width: "10%", targets: 3 },
          { width: "10%", targets: 4 },
          { width: "20%", targets: 5 },
          { width: "10%", targets: 6 }
        ]
        // "order": [[ 1, 'asc' ]]
    } );

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    //asdaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
    //asddddddddddddddddddddddddddddddddddddddddddddddddddddd

    this.api().columns().every( function () {
        var column = this;
        var select = $('<select><option value=""></option></select>')
            .appendTo( $(column.footer()).empty() )
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
    

} );
</script>
@endpush
