@extends('layouts.lteapp')

@section('title-page')

<div class="row mb-2">
  <div class="col-sm-6">
    <h1>Keranjang Barang Keluar</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
      <li class="breadcrumb-item active">Keranjang Barang Keluar</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="card card-danger">
  <div class="card-header">
    <!-- <h3 class="card-title">Title</h3> -->

    <div class="card-tools">
      {{-- @php($last= date('Y');) --}}
      <select class="form-control" id="filter_ta">
        <option value="">-- Filter Tahun Anggaran --</option>
        @for ($i = date('Y'); $i >= 2015; $i--)
            <option value="{{ $i }}">{{ $i }}</option>
        @endfor
      </select>
      <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
        <i class="fas fa-minus"></i></button>
      <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
        <i class="fas fa-times"></i></button> -->
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover dataTable" id="table_data" style="width:100%;">
        <thead>
          <tr>
            <th>#</th>
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
                <form action="{{route('kurangBK')}}" method="post" enctype="multipart/form-data">
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

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Jumlah Barang Keluar</label>
                        <input type="text" class="form-control" name="jumlah_keluar">
                      </div>
                    </div>

                  </div>

                  <div class="form-group">
                    <label>Tanggal Keluar</label>
                    <input class="form-control" id="date-bk" name="tanggal_keluar" placeholder="yyyy-mm-dd" type="date" >
                    
                  </div>

                  <div class="form-group">
                    <label>di Ambil Oleh</label>
                    <input type="text" class="form-control" name="diambil">
                  </div>

                  <div class="form-group">
                    <label>Keperluan</label>
                    <textarea name="keperluan" rows="3" cols="80" class="form-control"></textarea>

                  </div>

                  <button type="submit" name="button" class="btn btn-primary">Input</button>
                </form>

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
$(function () {
  $('#id_barang').change(function(){
    // Department id
    var id = $(this).val();
    // AJAX request 
    $.ajax({
      url: 'barangkeluar/fetch/'+id,
      type: 'get',
      dataType: 'json',
      success: function(response){

        var satuan = response['data'][0].satuan;
        var sekarang = response['data'][0].jumlah_sekarang;
        var tahun = response['data'][0].tahun_anggaran;

        // $("#linkWA").show();
        $("#satuan").attr("value", satuan);
        $("#sisastok").attr("value", sekarang);
        $("#tahun").attr("value", tahun);

      }
    });
    
  });

});

function ConfirmDelete()
{
  var x = confirm("Are you sure you want to delete?");
  if (x)
    return true;
  else
    return false;
}

$(function () {
  
  var table = $('#table_data');

  table.DataTable({
    // sDom: 'rt<"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
    // dom: 'Bfrtip',
    // buttons: [
    // 	'copy', 'csv', 'excel', 'pdf', 'print'
    // ]
    info: false,
    responsive: true,
    processing: true,
    serverSide: true,
    ajax: "{{ route('keranjangbarangkeluar') }}",
    columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'nama_barang', name: 'nama_barang'},
        {data: 'tahun_anggaran', name: 'tahun_anggaran'},
        {data: 'jumlah_keluar', name: 'jumlah_keluar'},
        {data: 'tanggal_keluar', name: 'tanggal_keluar'},
        {data: 'diambil', name: 'diambil'},
        {data: 'keperluan', name: 'keperluan'},
        {data: 'action', name: 'action', orderable: false, searchable: false, align:"center"},
    ],
    // columnDefs: [
    // 	{ className: 'text-center', targets: [0, 5] },
    // 	// { className: 'text-center', targets: [5] },
    // ],
    columnDefs:
      [
        { width: "5%", targets: 0, searchable: false, className: 'text-center' }, 
        { width: "20%", targets: 1 },
        { width: "10%", targets: 2 },
        { width: "10%", targets: 3, className: 'text-center' },
        { width: "10%", targets: 4 },
        { width: "10%", targets: 5, className: 'text-center'},
        { width: "25%", targets: 6 },
        { width: "10%", targets: 7, className: 'text-center' }
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

  
  $('#filter_ta').change(function(){
      var value = $(this).val();
      tablee.columns(2).search(value).draw();
  });

});

$(function () {
  $("#date-bk").datepicker({
        autoclose: true,
        todayHighlight: true,
        format : 'yyyy-mm-dd'
  });
});
</script>

@endpush
