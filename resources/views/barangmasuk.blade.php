@extends('layouts.lteapp')

@section('title-page')
<title>{{ $title }}</title>

<style>
  #form-id {
    display: none;
  }
</style>
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>Data Barang Masuk</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
      <li class="breadcrumb-item active">Barang Masuk</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="card card-primary">
  <div class="card-header">
    <!-- <h3 class="card-title">Title</h3> -->
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lg">
      Tambah Barang Masuk
    </button>
    @if(Auth::user()->level == 'superadmin' || Auth::user()->level == 'admin' )
  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-import">
    Import  Excel
  </button>
@endif

    <div class="card-tools">

    </div>
  </div>
  <div class="table-responsive">
    <div class="card-body">
      <table class="table table-bordered" id="table-barangmasuk" style="width:100%;">
        <thead>
          <tr>
            <th>No</th>
            <th>KIB</th>
            <th>Serial Number</th>
            <th>Nama Barang</th>
            <th>Jumlah Masuk</th>
            <th>Tanggal Masuk</th>
            <th>Surat BA</th>
            <th>User Log Input</th>
            <th>Last Update</th>
            <th>Act</th>
          </tr>
        </thead>
        <tbody>
         
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">

    </div>
  </div>
    <!-- /.card-footer-->
<div class="modal fade" id="modal-import">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Import Excel</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
    <form action="{{ route('barangmasuk.import') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
          <input type="file" name="file" id="file" class="form-control">
      </div>
     
      <button type="submit" class="btn btn-primary">Import</button>

      <a href="{{ route('exportbarangmasuktemplate') }}" class="btn btn-warning">Download Template</a>
  </form>
</div>
</div>
    </div>
  </div>
</div>

    <!-- modal -->
    <div class="modal fade" id="modal-lg">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Form Barang Masuk</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>


          <div class="modal-body">

            <div class="form-group">
              <!-- <label>Select</label> -->
              <select id="jenisinput" class="form-control">
                <option id='0' value="">- Pilih -</option>
                <option id='1' value="1">Input Barang Baru</option>
                <option id='2' value="2">Input Tambah Barang</option>
              </select>
            </div>


            <!-- ---------------------------------------------- -->

            <div id="barangbaru" style="margin:0 auto; display:none;">
              <form action="{{route('inputbarangmasuk')}}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                  <label>Tahun Anggaran</label>
                  <select class="form-control" name="tahun">
                    @for($year=2014; $year <= date('Y'); $year++) <option value="{{$year}}">{{$year}}</option>
                      @endfor
                  </select>
                </div>

                <div class="form-group">
                  <label>Jenis Barang</label>
                  <select class="form-control" name="jenisbarang">
                    <option value="">- Pilih -</option>
                    <option value="1">Aset</option>
                    <option value="2">Barang Habis Pakai</option>
                    <option value="11">Barang Pinjaman</option>
                    <option value="22">Lainnya</option>
                  </select>
                </div>
              
                <div class="form-group" id="form-id">
                  <label>Asal Barang</label>
                  <input type="text" class="form-control" name="asal_brg"  placeholder="Asal Barang">
                </div>

                <div class="form-group">
                  <label>Nama Barang</label>
                  <input type="text" class="form-control" name="nama_barang">
                </div>

                <div class="form-group">
                  <label>KIB</label>
                  <input type="number" class="form-control" name="kib" pattern="[0-9]+">
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Satuan</label>
                      <select class="form-control" name="satuan">
                        <option value="">- Pilih -</option>
                        <option value="Unit">Unit</option>
                        <option value="Roll">Roll</option>
                        <option value="Pack">Pack</option>
                      </select>
                    </div>
                  </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Barang Serial Number/Non Serial</label>
                    <select id="jenisinput2" class="form-control">
                        <option value="">- Pilih -</option>
                        <option value="serial_number">Input Serial Number</option>
                        <option value="non_serial">Input Non Serial Number</option>
                    </select>
                </div>
                </div>

              </div>
              
                <div class="form-group">
                  <div id="non_serial_section" style="margin:0 auto; display:none;">
                      <div class="form-group">
                          <label>Jumlah Barang Masuk</label>
                          <input type="number" class="form-control" name="jumlah_awal" placeholder="Enter Jumlah Barang">
                      </div>
                  </div>
                      <div id="serial_number_section" style="margin:0 auto; display:none;">
                          <label>Serial Number Barang</label>
                          <div id="dynamicAddRemove">
                              <div class="input-group mb-3">
                                  <input type="text" name="addMoreInputFields[0][serial_number]" placeholder="Enter serial number" class="form-control" />
                                  <div class="input-group-append">
                                      <button type="button" name="add" id="dynamic-ar" class="btn btn-outline-primary">Add Subject</button>
                                  </div>
                              </div>
                          </div>
                      </div>
              </div>

                <div class="form-group">
                  <label>Upload Dokumen</label>
                  <input type="file" class="form-control" name="filename">
                </div>

                <div class="form-group">
                  <label>Upload Foto</label>
                  <input type="file" class="form-control" name="image">
                </div>
                <div class="form-group">
                  <label>Lokasi Barang</label> 
                  <input type="text" class="form-control" name="lokasi">
                </div>

                <button type="submit" name="button" class="btn btn-primary">Input</button>


              </form>
            </div>


            <div id="tambahbarang" style="display:none;">
              <form action="{{route('tambahbarangmasuk')}}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                  <label>Pilih Barang</label>
                  <select class="select2 form-control" name='id_barang' id='id_barang'>
                    <option value="">- Pilih -</option>
                    @foreach ($select['data'] as $data)
                    <option value="{{$data->id}}">{{$data->nama_barang}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Stok Sekarang</label>
                      <input type="text" class="form-control" id="sisastok" readonly>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Satuan</label>
                      <input type="text" class="form-control" name="satuan" id="satuan" readonly>
                    </div>
                  </div>                
                </div>

                <div class="form-group">
                  <label>Barang Serial Number/Non Serial</label>
                  <select id="jenis" class="form-control">
                      <option value="">- Pilih -</option>
                      <option value="Bserial_number">Input Serial Number</option>
                      <option value="Bnon_serial" >Input Non Serial Number</option>
                  </select>
                </div>
              
              <div class="form-group">
                      <div id="Bserial_number" style="margin:0 auto; display:none;">
                          <label>Serial Number Barang</label>
                          <div id="dynamicAddRemove2">
                              <div class="input-group mb-3">
                                  <input type="text" name="addMoreInputFields[0][serial_number]" placeholder="Enter serial number" class="form-control" />
                                  <div class="input-group-append">
                                      <button type="button" name="add" id="dynamic-ar2" class="btn btn-outline-primary">Add Subject</button>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div id="Bnon_serial" style="margin:0 auto; display:none;">
                        <div class="form-group">
                            <label>Jumlah Barang No Serial</label>
                            <input type="number" class="form-control" id="jumlah_masuk" name="jumlah_masuk" placeholder="Input Jumlah Barang">
                        </div>
                    </div>
              </div>

                <button type="submit" name="button" class="btn btn-primary">Input</button>
              </form>

            </div>






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

const selectElement = document.querySelector('select[name="jenisbarang"]');
const formElement = document.getElementById('form-id'); 
const formElements = formElement.querySelectorAll('input, select, textarea'); 
selectElement.addEventListener('change', () => {
  if (selectElement.value === '11') {
    formElements.forEach((element) => {
      element.required = true;
    });
    formElement.style.display = 'block';
  } else {
    formElements.forEach((element) => {
      element.required = false;
    });
    formElement.style.display = 'none';
  }
});
    
    var i = 0;
    $("#dynamic-ar").click(function() {
      ++i;
      $("#dynamicAddRemove").append('<div class="input-group mb-3"><input type="text" name="addMoreInputFields[' + i +
        '][serial_number]" placeholder="Enter serial number" class="form-control" /><div class="input-group-append"><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></div></div>'
      );
    });

    $(document).on('click', '.remove-input-field', function() {
      $(this).closest('.input-group').remove();
    });


    var i = 0;
    $("#dynamic-ar2").click(function() {
      ++i;
      $("#dynamicAddRemove2").append('<div class="input-group mb-3"><input type="text" name="addMoreInputFields[' + i +
        '][serial_number]" placeholder="Enter serial number" class="form-control" /><div class="input-group-append"><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></div></div>'
      );
    });

    $(document).on('click', '.remove-input-field', function() {
      $(this).closest('.input-group').remove();
    });

    $(function() {
      $('.select2').select2();
    });


    $(document).ready(function() {
          var table = $('#table-barangmasuk').DataTable({
            serverSide: true,
            processing: true, 
            ajax: {
              url: "{{route('barangmasuk.get_barang')}}",
              type: "GET",
              lazyLoad: true,
              processing: true, 
            },
            columns: [
             {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
              {data: 'kib', name: 'kib'},
              {data: 'serial_number', name: 'serial_number'},
              {data: 'nama_barang', name: 'nama_barang'},
              {data: 'jumlah_masuk', name: 'jumlah_masuk'},
              {data: 'tanggal_masuk', name: 'tanggal_masuk'},
              {data: 'filename', name: 'filename', orderable: false, searchable: false, render: function(data, type, full, meta) {
                return data ? '<a href="{{url('uploadsa')}}/'+data+'" target="_blank" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" title="View"><i class="fa fa-eye"></i></a>' : '';
              }},
              {data: 'nama', name: 'nama'},
              {data: 'last_update', name: 'last_update'},
              {data: 'action', name: 'action', orderable: false, searchable: false, render: function(data, type, full, meta) {
                var deleteButton = '';
                if(full.status != 1) { 
                  // deleteButton = '<a href="{{route('deletebarangmasuk', '')}}/'+full.IDBM+'" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete" onclick="return confirm(\'Anda yakin ingin menghapus data ini?\')"><i class="fa fa-trash"></i></a>';
                  deleteButton = '<a href="javascript:void(0);" class="btn btn-sm btn-danger delete-btn" data-id="'+full.IDBM+'" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fa fa-trash"></i></a>';
                }
                return deleteButton;
              }},
            ]
  
          });
    
          $(document).on('click', '.delete-btn', function(e) {
    e.preventDefault();
    var deleteUrl = "{{ route('deletebarangmasuk', '') }}/" + $(this).data('id');
    
    Swal.fire({
        title: 'Are you sure?',
        // text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = deleteUrl;
        }
    });
});

        table.on('order.dt search.dt', function() {
        table.column(0, {
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

    $(function() {

      $('#id_barang').change(function() {

        var id = $(this).val();
        $.ajax({
          url: 'barangmasuk/fetch/' + id,
          type: 'get',
          dataType: 'json',
          success: function(response) {

            var satuan = response['data'][0].satuan;
            var sekarang = response['data'][0].jumlah_sekarang;

            $("#satuan").attr("value", satuan);
            $("#sisastok").attr("value", sekarang);

          }
        });

      });

    });

    $(document).ready(function() {
        $("#jenisinput2").change(function() {
            var selectedValue = $(this).val();
            if (selectedValue === "non_serial") {
                $("#non_serial_section").show();
                $("#serial_number_section").hide();
            } else if (selectedValue === "serial_number") {
                $("#non_serial_section").hide();
                $("#serial_number_section").show();
            } else {
                $("#non_serial_section").hide();
                $("#serial_number_section").hide();
            }
        }).trigger('change');
    });

    $(document).ready(function() {
        $("#jenis").change(function() {
            var selectedValue = $(this).val();
            if (selectedValue === "Bnon_serial") {
                $("#Bnon_serial").show();
                $("#Bserial_number").hide();
            } else if (selectedValue === "Bserial_number") {
                $("#Bnon_serial").hide();
                $("#Bserial_number").show();
            } else {
                $("#Bnon_serial").hide();
                $("#Bserial_number").hide();
            }
        }).trigger('change');
    });   
  </script>
  @endpush