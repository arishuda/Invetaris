@extends('layouts.lteapp')

@section('title-page')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Barang Rusak</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Barang Rusak</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card card-dark">
            <div class="card-header">
                <!-- <h3 class="card-title">Title</h3> -->
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lg">
                    Tambah Barang Rusak
                </button>
                &nbsp;
            </div>
        </div>
        <div class="table-responsive">
            <div class="card-body">
                <table class="table table-hover" id="table-rusak">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Barang</th>
                            <th>Tanggal Rusak</th>
                            <th>Jumlah Rusak</th>
                            <th>Serial Number</th>
                            <th>Wilayah</th>
                            <th>Status</th>
                            <th>User Input</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('sweetalert::alert')
                        @foreach ($query as $data)
                            <tr>
                                <td align="center"></td>
                                <td>{{ $data->nama_barang }}</td>
                                <td>{{ $data->created_at }}</td>
                                <td>{{ $data->jumlah_rusak }} {{ $data->satuan }}</td>
                                <td>{{ $data->sn }}</td>
                                <td>{{$data->wilayah}}</td>
                                <td>{{ $data->status }}</td>
                                <td>{{ $data->NAMA }}</td>
                                <td>
                                    @if ($data->aktif == 0)
                                        <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip"
                                            data-placement="bottom">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    @else
                                    <form id="update-form-{{ $data->id }}" action="{{ route('updatebr', $data->id) }}" method="post">
                                        @csrf 
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" onclick="confirmUpdate({{ $data->id }})">
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
        </div>
    </div>




    <!-- modal -->
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Form Barang Rusak</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <div class="modal-body">
                    <form action="{{ route('tambahrusak') }}" method="post" enctype="multipart/form-data">
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

                            {{-- <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Jumlah Barang rusak</label> <input type="text" class="form-control"
                                        name="jumlah_rusak">
                                </div>
                            </div> --}}

                            <div class="col-md">
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
                    

                        <div class="form-group">
                            <label>Pilih Kota</label>
                            <select class="select2 form-control" id="city-select" name="city-select">
                                <option value="">- Pilih -</option>
                            </select>
                        </div>

                        <div class="form-group" id="regions-container" style="display: none;">
                            <label>Pilih Daerah</label>
                            <select class="select2 form-control" name="wilayah" id="regions-list">
                                <option value="">- Pilih -</option>
                            </select>
                        </div>
                        
                        <input type="hidden" class="id_qr" name="id_qr[]" value="">

                        <div class="row">
                            
                            {{-- <div class="col-md">
                                <div class="form-group">
                                    <label>Wilayah</label>
                                    <input type="text" class="form-control" name="wilayah" id="wilayah" required>
                                </div>
                            </div> --}}
                        </div>

                        
                    
                        <!-- Regions Display -->
                        {{-- <h2>Regions</h2>
                        <ul id="regions-list">
                            <!-- Regions will be dynamically loaded here -->
                        </ul> --}}

                      

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
    </div>
    <!-- /.card -->
@endsection


@push('scripts')
    <script>

$(document).ready(function() {
    $.ajax({
        url: '{{ route("RegionsAjax") }}', 
        method: 'GET',
        success: function(response) {
            const cities = response.cities;
            const citySelect = $('#city-select');
            cities.forEach(function(city) {
                citySelect.append(new Option(city.city, city.city));
            });

            
            citySelect.select2();

            citySelect.change(function() {
                const selectedCity = $(this).val();

                if (selectedCity) {
                
                    $('#regions-container').show();
                    fetchRegions(selectedCity);
                } else {
                
                    $('#regions-container').hide();
                    $('#regions-list').empty().append(new Option('- Pilih -')).select2();
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error fetching cities:', error);
        }
    });

    function fetchRegions(city) {
        $.ajax({
            url: '{{ route("RegionsAjax") }}',
            method: 'GET',
            data: { city: city },
            success: function(response) {
                const regions = response.regions;
                const regionsList = $('#regions-list');
                regionsList.empty(); 

                
                regionsList.append(new Option('- Pilih -'));
                regions.forEach(function(region) {
                    regionsList.append(new Option(region.name, region.name));
                });

                regionsList.select2();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching regions:', error);
            }
        });
    }
});




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
            $('.select2').select2();
        });

        var maxOptions = 0;
        var currentCount = 0;

        $(function() {
            $('#id_barang').change(function() {
                var id = $(this).val();
                $.ajax({
                    url: 'barangrusak/fetch/' + id,
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
                            $('#addButton').prop('disabled',currentCount >= maxOptions);
                        }
                    }
                });

                // Clear previously selected serial numbers
                $('#additionalSelects').empty();


            });

            $('#addButton').click(function() {
                var id = $('#id_barang').val();

                $.ajax({
                    url: 'barangrusak/fetch/' + id, 
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.serial_numbers) {
                            var newSelect = $(
                                '<div class="form-group">' +
                                '<div class="d-flex align-items-center">' +
                                '<select class="select2 form-control" name="serial_number[]">' +
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
                            $('#addButton').prop('disabled',currentCount >= maxOptions);
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

                currentCount--;
                $('#addButton').prop('disabled',currentCount >= maxOptions);
            });
        });

        $(function() {
            var t = $('#table-rusak').DataTable({
                "columnDefs": [{
                        // width: "5%",
                        searchable: false,
                        responsive: true,
                        // "orderable": false,
                        targets: 0
                    },
                    // { width: "10%", targets: 1 },
                    // { width: "43%", targets: 2 },
                    // { width: "10%", targets: 3 },
                    // { width: "10%", targets: 4 },
                    // { width: "10%", targets: 5 },
                    // { width: "15%", targets: 6 },
                    // { width: "7%", targets: 7 }
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
