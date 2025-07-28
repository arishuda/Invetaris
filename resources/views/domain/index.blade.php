@extends('layouts.lteapp')

@section('title-page')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Domain</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active"> Domain</li>
            </ol>
        </div>
    </div>
    @include('domain.add')
    {{-- @include('regions.edit') --}}
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card card-success">
            <div class="card-header">
                <!-- <h3 class="card-title">Title</h3> -->
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lg">
                    Tambah Domain
                </button>
                &nbsp;
            </div>
        </div>
        <div class="table-responsive">
            <div class="card-body">
                <table class="table table-hover dataTable" id="table-domain" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Expired Date</th>
                            <th>Registrar</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Company</th>
                            <th>Remark</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('sweetalert::alert')
                        @foreach ($domains as $data)
                            <tr>
                                <td align="center"></td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->expired_date }}</td>
                                <td>{{ $data->registrar }}</td>
                                <td>{{ $data->email }}</td>
                                <td>{{ $data->password }}</td>
                                <td>{{ $data->company }}</td>
                                <td>{{ $data->remark }}</td>
                                <td>

                                   <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal"
                                        data-target="#edit-{{ $data->id }}" data-placement="bottom" title="Edit"><i
                                            class="fa fa-edit"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmDelete({{ $data->id }})" data-placement="bottom" title="Hapus">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                    <!-- Hidden Form -->
                                    <form id="delete-form-{{ $data->id }}"
                                        action="{{ route('deletedomain', $data->id) }}" style="display: none;">
                                        @csrf
                                    </form>

                                    
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

    <div class="modal fade" id="edit-{{ $data->id }}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Stok Barang</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <div class="modal-body">


                    <form action="{{ route('updatedomain', $data->id) }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                        <div class="col-md">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="{{$data->nama}}">
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Expired Date</label>
                                <input type="date" class="form-control" id="expired_date" name="expired_date" value="{{$data->expired_date}}">
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <label>Registrar</label>
                            <input type="text" class="form-control" id="registrar" name="registrar" value="{{$data->registrar}}">
                        </div>

                        {{-- <div class="row" --}}
                        <div class="col-sm-4">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{$data->email}}">
                        </div>
                        <div class="col-sm-4">
                            <label>Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="{{$data->password}}">
                        </div>
                        <div class="col-sm-4">
                            <label>Company</label>
                            <input type="text" class="form-control" id="company" name="company" value="{{$data->company}}">
                        </div>
                        <div class="col-sm-4">
                            <label>Remark</label>
                            <input type="text" class="form-control" id="remark" name="remark" value="{{$data->remark}}">
                        </div>
                    </div>
                    <br>

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
        <!-- /.modal -->

        <!-- ---------------------------------------------------------------------------- -->

      
        </tbody>
        </table>
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

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        $(document).ready(function() {
            var t = $('#table-domain').DataTable({
                responsive: true,
                columnDefs: [{
                    orderable: false,
                    searchable: false,
                    processing: true,
                    serverSide: true,
                    targets:0
                }]
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
    </script>
@endpush
