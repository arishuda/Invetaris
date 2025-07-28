@extends('layouts.lteapp')

@section('title-page')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Regions</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active"> Regions</li>
            </ol>
        </div>
    </div>
    @include('regions.add')
    {{-- @include('regions.edit') --}}
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card card-success">
            <div class="card-header">
                <!-- <h3 class="card-title">Title</h3> -->
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lg">
                    Tambah Region
                </button>
                &nbsp;
            </div>
        </div>
        <div class="table-responsive">
            <div class="card-body">
                <table class="table table-hover" id="table-regions">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Nama Lokasi</th>
                            <th>Level</th>
                            <th>Kota</th>
                            <th>Latitude</th>
                            <th>Address</th>
                <th>Postal Code</th>
                <th>Telephone</th>
                <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('sweetalert::alert')
                        @foreach ($regions as $data)
                            <tr>
                                <td align="center"></td>
                                <td>{{$data->code}}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->level }}</td>
                                <td>{{$data->city}}</td>
                                <td>{{ $data->latitude }},{{$data->longitude}}</td>
                                @if ($data->details)
                               
                                    <td>{{ $data->details['address'] ?? '-' }}</td>
                                    <td>{{ $data->details['postal_code'] ?? '-' }}</td>
                                    <td>{{ $data->details['telphone'] ?? '-' }}</td>
                                    <td>{{ $data->details['email'] ?? '-' }}</td>
                               
                            @endif
                                <td>
                                   
                                    <a href="{{ route('deleteregions', ($data->id)) }}" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="fa fa-trash"></i></a> 
                                    {{-- <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#edit-{{$regions->id}}" data-placement="bottom" title="Edit"><i class="fa fa-edit"></i></button> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if (session('success'))
    <p>{{ session('success') }}</p>
@endif

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


        <form action="{{ route('editregions', $data->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
            <label>Lokasi Barang</label>
            <input type="text" class="form-control" name="lokasi" value="{{$data->name}}">
            </div>
            <div class="form-group">
                <label>Lokasi Barang</label>
                <input type="text" class="form-control" name="lokasi" value="{{$data->city}}">
                </div>


            <button type="submit" name="button" class="btn btn-success">Update</button>
          </form>



        </div>

      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>



    <!-- modal -->
    
    <!-- /.modal -->
    </div>
    </div>
    <!-- /.card -->
@endsection


@push('scripts')
    <script>

function confirmUpdate(id) {
    Swal.fire({
        title: 'Apakah Anda Yakin?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('update-form-' + id).submit();
        }
    });
}

    

        $(function() {
            var t = $('#table-regions').DataTable({
                "columnDefs": [{
                        searchable: false,
                        responsive: true,
                        serverSide: true,
                        targets: 0
                    },
                ]
            });

            t.on('order.dt search.dt', function() {
                t.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

        });

        $(function() {
      $("#jenisinput").change(function() {
        if ($("#0").is(":selected")) {
          $("#barangbaru").hide();
          $("#tambahbarang").hide();
        } else if ($("#1").is(":selected")) {
          $("#barangbaru").show();
          $("#tambahbarang").hide();
        } else {
          $("#barangbaru").hide();
          $("#tambahbarang").show();
        }
      }).trigger('change');
    });
    </script>
@endpush
