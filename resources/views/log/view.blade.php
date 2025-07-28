@extends('layouts.lteapp')

@section('title-page')
<div class="row mb-2">
<div class="col-sm-6">
    <h1>Log e-aset</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
    <li class="breadcrumb-item active">Log e-aset</li>
    </ol>
</div>
</div>
@endsection

@section('content')
<div class="card card-primary">
<div class="card card-dark">
<div class="card-header">
    <!-- <h3 class="card-title">Title</h3> -->
    &nbsp;
    </div>
</div>
<div class="table-responsive">
  <div class="card-body">
    <table class="table table-bordered" id="table-log">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Status</th>
                <th>User Log Input</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($query as $data)
        <tr>
            <td align="center"></td>
            <td>{{$data->nama_barang}}</td>
            <td>{{$data->log}}</td>
            <td>{{$data->NAMA}}</td>
            <td>{{$data->desc}}</td>
            <td>{{$data->last}}</td>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
</div>
  <!-- /.card-body -->
<div class="card-footer">
    
</div>
<!-- /.card-footer-->



  <!-- modal -->


       
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
</div>
<!-- /.card -->

@endsection


@push('scripts')
<script>
$(function () {
    var t = $('#table-log').DataTable( {
      
        "columnDefs":
        [
          {
            searchable: false,
            responsive: true,
            serverSide: true,
            targets: 0
          },  
        ]
    } );

    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

} );


</script>
@endpush
