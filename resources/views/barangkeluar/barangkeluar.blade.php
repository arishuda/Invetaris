@extends('layouts.lteapp')

@section('title-page')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Barang Keluar</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Barang Keluar</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card card-danger">
        <div class="card-header">
            <!-- <h3 class="card-title">Title</h3> -->
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lg">
                Tambah Barang Keluar
            </button>
            <div class="card-tools">
                {{-- @php($last= date('Y');) --}}
                <select class="form-control" id="filter_ta">
                    <option value="">-- Filter Tahun Anggaran --</option>
                    @for ($i = date('Y'); $i >= 2015; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
        
        <div class="table-responsive">
        <div class="card-body">
            
                <table class="table table-hover dataTable" id="table_data" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            {{-- <th>SN</th> --}}
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
                        @include('sweetalert::alert')
                    </tbody>
                </table>

            </div>
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
                        <h4 class="modal-title">Form Barang Keluar</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>


                    <div class="modal-body">

                        <div id="tambahbarang">
                            <form action="{{ route('kurangBK') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Pilih Barang</label>
                                    <select class="select2 form-control" name='id_barang' id='id_barang'>
                                        <option value="">- Pilih -</option>
                                        @foreach ($select['data'] as $data)
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
                                    <label>Tanggal Keluar</label>
                                    <input class="form-control" name="tanggal_keluar"
                                        placeholder="yyyy-mm-dd" type="date" required>
                                </div>

                                <div class="form-group">
                                    <label>di Ambil Oleh</label>
                                    <input type="text" class="form-control" name="diambil" required>
                                </div>

                                <div class="form-group">
                                    <label>Keperluan</label>
                                    <textarea name="keperluan" rows="3" cols="80" class="form-control" required></textarea>

                                </div>

                                <button type="submit" name="button" class="btn btn-primary">Input</button>
                            </form>

                        </div>
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
        //Select 2
        $(function() {
            $('.select2').select2();
        });



        //Post Barang Keluar
$(function() {
    var maxOptions = 0; 
    var currentCount = 0;

    $('#id_barang').change(function() {
        var id = $(this).val();

        $.ajax({
            url: 'barangkeluar/fetch/' + id,
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

                    maxOptions = sekarang; 
                    currentCount = 0; 
                    $('#additionalSelects').empty(); 
                    $('#addButton').prop('disabled', currentCount >= maxOptions); 
                }
            }
        });
    });

    $('#addButton').click(function() {
        var id = $('#id_barang').val();

        $.ajax({
            url: 'barangkeluar/fetch/' + id,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                if (response && response.serial_numbers) {
                    var newSelect = $('<div class="form-group">' +
                        '<div class="d-flex align-items-center">' +
                        '<select class="select2 form-control mr-0" name="serial_number[]">' +
                        '<option value="">- Pilih -</option>' +
                        '</select>' +
                        '<input type="hidden" name="id_qr[]" value="">'+
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

                    currentCount++; 
                    $('#addButton').prop('disabled', currentCount >= maxOptions); // 
                }
            }
        });
    });

    $(document).on('change','select[name="serial_number[]"]',function(){
    var selectedOption = $(this).find('option:selected');
    var idQr =selectedOption.data('idqr');
    $(this).siblings('input[name="id_qr[]"]').val(idQr);
    });

    $(document).on('click', '.remove-input-field', function() {
        $(this).closest('.form-group').remove();

        currentCount--; // Decrement the current count
        $('#addButton').prop('disabled', currentCount >= maxOptions); // Enable/disable addButton based on the count
    });
});


        function ConfirmDelete() {
            var x = confirm("Are you sure you want to delete?");
            if (x)
                return true;
            else
                return false;
        }

        $(function() {

            var table = $('#table_data');

            table.DataTable({
                info: false,
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('barangkeluar') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    // {
                    //     data:'serial_number',
                    //     name: 'serial_number'
                    // },
                    {
                        data: 'nama_barang',
                        name: 'nama_barang'
                    },
                    {
                        data: 'tahun_anggaran',
                        name: 'tahun_anggaran'
                    },
                    {
                        data: 'jumlah_keluar',
                        name: 'jumlah_keluar'
                    },
                    {
                        data: 'tanggal_keluar',
                        name: 'tanggal_keluar'
                    },
                    {
                        data: 'diambil',
                        name: 'diambil'
                    },
                    {
                        data: 'keperluan',
                        name: 'keperluan'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        align: "center"
                    },
                ],
                // columnDefs: [
                // 	{ className: 'text-center', targets: [0, 5] },
                // 	// { className: 'text-center', targets: [5] },
                // ],
                columnDefs: [{
                        width: "5%",
                        targets: 0,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                    width: "10%",
                    targets: 3,
                    className: 'text-center'
                    },
                    {
                        width: "10%",
                        targets: 4,
                    }
                ],
                rowReorder: {
                    selector: 'td:nth-child(1)'
                },

            });

            var tablee = $('#table_data').DataTable();

            $('#search').on('keyup', function() {
                tablee.search(this.value).draw();
            });

            $.fn.dataTable.ext.errMode = () => tablee.draw();

            $('#search').each(function() {
                $(this).attr('data-remember', $(this).val());
            });

            $('#reset').click(function() {
                $('#search').each(function() {
                    $(this).val($(this).attr('data-remember'));
                });

                tablee.search(this.value).draw();
            });


            $('#filter_ta').change(function() {
                var value = $(this).val();
                tablee.columns(2).search(value).draw();
            });

        });

        $(function() {
            $("#date-bk").datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd'
            });
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
