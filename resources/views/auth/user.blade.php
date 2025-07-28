@extends('layouts.lteapp')

@section('title-page')

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <b> Data Teknisi </b> <br>
                Sarana dan Prasarana Teknologi Informasi Pusdatin PMPTSP
                <a href="{{ route('user.create') }}" class="btn btn-primary btn-rounded btn-fw pull-right"><i class="fa fa-plus"></i> Tambah User</a>
            </div>
        </div>
    </div>
</div>

<div class="row" style="">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">

            <div class="card-body">
                {{-- <h4 class="card-title">Data Teknisi</h4> --}}

                <div class="table-responsive">
                    <table id="table" class="table table-striped" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Name</th>
                                <th>Jabatan</th>
                                <th>Level - Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $data)

                            <tr>
                                <td align="center"></td>
                                <td class="py-1">
                                    @if ($data->gambar)
                                    <img src="{{ url('images/user', $data->gambar) }}" alt="User Image" style="margin-right: 10px;" width="40px" align="center" />
                                    @else
                                    <img src="{{ url('lte/dist/img/avatar5.png') }}" alt="image" style="margin-right: 10px;"  width="40px" align="center" />
                                    @endif

                                  
                                </td>
                                <td>  {{ $data->name }}</td>
                                <td>
                                    <label>
                                        {{ $data->jabatan }}
                                    </label>
                                </td>
                                <td>
                                    {!! labelLevel($data->level) !!} {!! labelRole($data->role) !!}
                                </td>
                                <td>
                                    @if($data->active == 1)
                                    <a class="badge bg-success" for="active">Active</a>
                                    @else
                                    <a class="badge bg-danger" for="active">Inactive</a>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a class="btn btn-success btn-sm" href="{{ route('user.edit', \Hashids::encode($data->id)) }}">
                                            <i class="fa fa-pencil-alt" aria-hidden="true"></i>
                                        </a>
                                        &nbsp;
                                        <form action="{{ route('user.destroy', $data->id) }}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus data ini?')">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>

                                    </div>

                                </td>
                            </tr>
                            @endforeach
                            @include('sweetalert::alert')
                        </tbody>
                    </table>
                </div>
                {{-- {!! $datas->links() !!} --}}
            </div>
        </div>
    </div>
</div>
@endsection

{{-- @section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugin/fancybox/dist/jquery.fancybox.min.css')}}">
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css')}}">
@endsection

@section('js')
<script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script> --}}
@push('scripts')

<script type="text/javascript">
    // $(document).ready(function() {
    //     $('#table').DataTable({
    //         "ordering": false
    
    //         "serverSide": true,
    //     });
    // });
    $(function () {
    var t = $('#table').DataTable( {
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

{{-- @section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#table').DataTable({
                "iDisplayLength": 10
            });

        });
    </script>
@stop --}}