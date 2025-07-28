@extends('layouts.lteapp')

@section('title-page')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Peminjaman Barang</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Peminjaman Barang</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card card-info">
        <div class="card-header">
            <!-- <h3 class="card-title">Title</h3> -->
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lg">
                Tambah Peminjaman Barang
            </button>
        </div>
        <div class = "table-responsive">
            <div class="card-body">
                <table class="table table-bordered" id="table-pinjam">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jumlah Pinjam</th>
                            <th>Diambil</th>
                            <th>Tanggal Kembali</th>
                            <th>Jumlah Kembali</th>
                            <th>Keperluan</th>
                            <th>Status</th>
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
                                <td>{{ $data->nama_barang }}</td>
                                <td>{{ $data->tanggal_pinjam }}</td>
                                <td>{{ $data->jumlah_pinjam }} {{ $data->satuan }}</td>
                                <td>{{ $data->dipinjam }}</td>
                                <td>
                                    @if ($data->jumlah_kembali == null)
                                        -
                                    @else
                                        {{ $data->tanggal_kembali }}
                                    @endif
                                </td>
                                <td>
                                    @if ($data->jumlah_kembali == null)
                                        -
                                    @else
                                        {{ \DB::table('logkembali')->where('id_peminjaman', $data->id)->sum('jumlah_kembali') }}
                                        {{ $data->satuan }}
                                    @endif
                                </td>
                                <td>{{ $data->keperluan }}</td>
                                <td align="center">
                                    @if ($data->jumlah_kembali == null)
                                        <a href="{{ route('inputtd', \Hashids::encode($data->id)) }}"
                                            class="btn btn-danger btn-xs " value="belum_kembali">Belum Kembali</a>
                                        <!-- <span class="badge badge-danger">belum kembali</span> -->
                                    @elseif(
                                        $data->jumlah_pinjam !=
                                            \DB::table('logkembali')->where('id_peminjaman', $data->id)->sum('jumlah_kembali'))
                                        <a href="{{ route('inputtd', \Hashids::encode($data->id)) }}"
                                            class="btn btn-warning btn-xs" value="kembali_sebagian">Kembali Sebagian</a>
                                    @else
                                        <a href="{{ route('inputtd', \Hashids::encode($data->id)) }}"
                                            class="btn btn-success btn-xs" value="sudah_kembali">Sudah kembali</a>
                                    @endif

                                </td>
                                <td><a href="{{ route('editpeminjaman', \Hashids::encode($data->id)) }}" type="button"
                                        class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom"
                                        title="Edit"><i class="fa fa-edit"></i></button></a>
                                    @if (Auth::user()->level == 'superadmin' && 'admin')
                                        <a href="{{ route('deletepeminjaman', $data->id) }}"
                                            class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom"
                                            title="Delete"
                                            onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i
                                                class="fa fa-trash"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
          </div>

            <!-- /.card-body -->
            <!-- <div class="card-footer">
        Footer
      </div> -->
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
                            <form action="{{ route('inputpinjam') }}" method="post">
                                @csrf

                                <div class="form-group">
                                    <label>Pilih Barang</label>
                                    <select class="select2 form-control" name='id_barang' id='id_barang'>
                                        <option value="">- Pilih -</option>
                                        @foreach ($select as $data)
                                            <option value="{{ $data->id }}">{{ $data->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="tahun">Tahun Anggaran</label>
                                            <input type="text" class="form-control" id="tahun" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="sisastok">Stok Sekarang</label>
                                            <input type="text" class="form-control" id="sisastok" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="satuan">Satuan</label>
                                            <input type="text" class="form-control" name="satuan" id="satuan"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="serial_number">Pilih Serial Number</label>
                                            <div class="input-group">
                                                <button id="addButton" class="btn btn-primary btn-block"
                                                    type="button">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="additionalSelects">

                                </div>
                                <input type="hidden" class="id_qr" name="id_qr[]" value="">

                                <div class="form-group">
                                    <label>di Ambil Oleh</label>
                                    <input type="text" class="form-control" name="dipinjam" required>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label>Keperluan</label>
                                    <textarea name="keperluan" rows="3" cols="80" class="form-control" required></textarea>
                                    <!-- <input type="text" class="form-control" name="diambil"> -->
                                </div>
                                <button type="submit" name="button" class="btn btn-primary">Input</button>
                            </form>
                        </div>
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

        

        $(function() { 
        
            $('#id_barang').change(function() {
                var id = $(this).val();
                $.ajax({
                    url: 'peminjaman/fetch/' + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.data) {
                            var satuan = response.data.satuan;
                            var sekarang = response.data.jumlah_sekarang;
                            var tahun = response.data.tahun_anggaran;

                            $("#satuan").val(satuan);
                            $("#sisastok").val(sekarang);
                            $("#tahun").val(tahun);
                            $('#additionalSelects').empty();
                        }
                    }
                });
            });

            $('#addButton').click(function() {
                var id = $('#id_barang').val();
                $.ajax({
                    url: 'peminjaman/fetch/' + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.serial_numbers) {
                            var newSelect = $('<div class="form-group">' +
                                '<div class="d-flex align-items-center">' +
                                '<select class="select2 form-control mr-0" name="serial_number[]">' +
                                '<option value="">- Pilih -</option>' +
                                '</select>' +
                                '<input type="hidden" name="id_qr[]" value="">' +
                                '<button type="button" class="btn btn-outline-danger remove-input-field">Delete</button>' +
                                '</div>' +
                                '</div>');

                            var selectElement = newSelect.find('select');
                            var idQrInput = newSelect.find('input[name="id_qr[]"]');

                            $.each(response.serial_numbers, function(index, item) {
                                selectElement.append($('<option>', {
                                    value: item.serial_number,
                                    text: item.serial_number,
                                    'data-idqr': item.id_qr
                                }));
                            });

                            $('#additionalSelects').append(newSelect);
                            selectElement.select2();
                        }
                    }
                });
            });

            $(document).on('change', 'select[name="serial_number[]"]', function() {
                var selectedOption = $(this).find('option:selected');
                var idQr = selectedOption.data('idqr');
                $(this).siblings('input[name="id_qr[]"]').val(idQr);
            });

            $(document).on('click', '.remove-input-field', function() {
                $(this).closest('.form-group').remove();
            });
        });


        $(function() {
            var t = $('#table-pinjam').DataTable({


                "columnDefs": [{
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
                    $("#asn-1").hide();
                    $("#asn-2").hide();
                    $("#nonasn").hide();
                } else if ($("#1").is(":selected")) {
                    $("#asn-1").show();
                    $("#asn-2").show();
                    $("#nonasn").hide();
                } else {
                    $("#asn-1").hide();
                    $("#asn-2").hide();
                    $("#nonasn").show();
                }
            }).trigger('change');
        });
    </script>
@endpush
