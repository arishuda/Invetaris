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
    {{-- <div class="card-tools"> --}}
    <nav class="nav nav-pills nav-justified" id="myTab">
      <a class="nav-item nav-link active text-white" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Aset</a>
      <a class="nav-item nav-link text-white" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Barang Habis Pakai/Lainnya</a>
      <a class="nav-item nav-link text-white" id="pills-profile-tab2" data-toggle="pill" href="#pills-profile2" role="tab" aria-controls="pills-profile" aria-selected="false">Barang Pinjaman</a>
      {{-- <a href="{{ route('barangmasuk') }}" class="btn btn-primary nav-item">Barang Masuk</a>
      <a href="{{ route('barangkeluar') }}" class="btn btn-danger nav-item">Barang Keluar</a> --}}
    </nav>

      {{-- <a href="{{ route('peminjaman') }}" class="btn btn-success">Peminjaman Barang</a> --}}
      <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
          <i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fas fa-times"></i></button> -->
      {{-- </div> --}}
  </div>
  <div class="card-body">
    <div class="tab-content" id="pills-tabContent">
      <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
        <a href="{{ route('exportstokAset') }}" class="btn btn-success" data-toggle="" data-target="">
          Export Excel
        </a>
        <br><br>
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="table-stok-1">
            <thead>
              <tr>
                <th>No</th>
                <th>KIB</th>
                <th>Nama Barang</th>
                <th>Tahun Pengadaan</th>
                <th>Barang Rusak</th>
                <th>Barang Dipinjam</th>
                <th>Barang Keluar</th>
                <th>Stok Sekarang</th>
                <th>Stok Awal</th>
                <th>Lokasi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($Aquery as $data)
              <tr>
                <td align="center"></td>
                <td>{{$data->kib}}</td>
                <td><a href="{{ route('detailstok', \Hashids::encode($data->id)) }}">{{$data->nama_barang}}</a></td>
                <td align="center">{{$data->tahun_anggaran}} </td>
                <td align="center"> {{\DB::table('barangrusak')->where('id_barang', $data->id)->where('aktif', 1)->sum('jumlah_rusak')}}
                </td>
                <td align="center">
                  {{\DB::table('peminjaman')->where('id_barang', $data->id)->where('aktif', 1)->sum('jumlah_pinjam')}}
                </td>
                <td align="center">
                  {{\DB::table('historybarangkeluar')->where('id_barang', $data->id)->where('aktif', 1)->sum('jumlah_keluar')}}
                </td>
                <td align="center">{{$data->jumlah_sekarang}}</td>
                <td align="center">{{$data->jumlah_awal}}</td>
                <td>{{$data->lokasi}}</td>
                <td>
                  <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#edit-{{$data->id}}" data-placement="bottom" title="Edit">
                    <i class="fa fa-edit"></i></button>
                  <a href="{{route('qrcodestok',\Hashids::encode($data->id))}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Qrcode">
                    <i class="fa fa-qrcode"></i></a>
                    <a href="{{ route('deletestok', \Hashids::encode($data->id)) }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Delete" onclick="confirmDelete(event, '{{ route('deletestok', $data->id) }}')"><i class="fa fa-trash"></i></a>
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


                      <form action="{{ route('updatestok', $data->id) }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">

                          <img id="thumb_preview" src="{{ !empty($data->image) ? asset('stokbarang/'.$data->image) : asset('image/datakom-logo.jpeg') }}" alt="your image" height="150px" />
                        </div>

                        <div class="form-group">
                          <label>Foto Barang</label>
                          <input type="file" accept="image/*" class="form-control" name="image" id="thumb_image">
                        </div>

                        <div class="form-group">
                          <label>Tahun Anggaran</label>
                          <select class="form-control" name="tahun">
                            <option value="{{$data->tahun_anggaran}}">{{$data->tahun_anggaran}}</option>
                            <option value="">-------</option>
                            @for($year=2014; $year <= date('Y'); $year++) <option value="{{$year}}">{{$year}}</option>
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

                        <div class="form-group">
                          <label>KIB</label>
                          <input type="text" class="form-control" name="kib" value="{{$data->kib}}">
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
                <!-- /.modal -->

                <!-- ---------------------------------------------------------------------------- -->

                @endforeach
            </tbody>
          </table>
        </div>
      </div>


      <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
        <a href="{{ route('exportstok') }}" class="btn btn-success" data-toggle="" data-target="">
          Export Excel
        </a>
        <br><br>
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="table-stok-2">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Tahun Pengadaan</th>
                <th>Barang Keluar</th>
                <th>Stok Sekarang</th>
                <th>Stok Awal</th>
                <th>Lokasi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($Bquery as $data)
              <tr>
                <td align="center"></td>
                {{-- <!-- data-toggle="modal" data-target="#view-{{$data->id}}" --> --}}
                <td><a href="{{ route('detailstok', \Hashids::encode($data->id)) }}">{{$data->nama_barang}}</a></td>
                <td align="center">{{$data->tahun_anggaran}}</td>
                <td align="center">
                  {{\DB::table('barangkeluar')->where('id_barang', $data->id)->where('aktif', 1)->sum('jumlah_keluar')}}
                </td>
                <td align="center">{{$data->jumlah_sekarang}}</td>
                <td align="center">{{$data->jumlah_awal}}</td>
                <td>{{$data->lokasi}}</td>
                <td align="center">
                  <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#edit-{{$data->id}}" data-placement="bottom" title="Edit"><i class="fa fa-edit"></i></button>
                  <a href="/stok/qrcode/{{\Hashids::encode($data->id)}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Qrcode">
                    <i class="fa fa-qrcode"></i></a>
                  <a href="{{ route('deletestok', \Hashids::encode($data->id)) }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Delete" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="fa fa-trash"></i></a>

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


                      <form action="{{ route('updatestok', $data->id) }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">

                          <img id="thumb_preview_2" src="{{ !empty($data->image) ? asset('stokbarang/'.$data->image) : asset('image/datakom-logo.jpeg') }}" alt="your image" height="150px" />
                        </div>

                        <div class="form-group">
                          <label>Foto Barang</label>
                          <input type="file" accept="image/*" class="form-control" name="image" id="thumb_image_2">
                        </div>

                        <div class="form-group">
                          <label>Tahun Anggaran</label>
                          <select class="form-control" name="tahun">
                            <option value="{{$data->tahun_anggaran}}">{{$data->tahun_anggaran}}</option>
                            <option value="">-------</option>
                            @for($year=2014; $year <= date('Y'); $year++) <option value="{{$year}}">{{$year}}</option>
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

      </div>

      <div class="tab-pane fade" id="pills-profile2" role="tabpanel" aria-labelledby="pills-profile-tab2">
        <a href="{{ route('exportPinjaman') }}" class="btn btn-success" data-toggle="" data-target="">
          Export Excel
        </a>
        <br><br>
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="table-stok-3">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Tahun Pengadaan</th>
                {{-- <th>Barang Keluar</th> --}}
                <th>Asal Barang</th>
                <th>Stok Sekarang</th>
                <th>Stok Awal</th>
                <th>Lokasi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($Cquery as $data)
              <tr>
                <td align="center"></td>
                {{-- <!-- data-toggle="modal" data-target="#view-{{$data->id}}" --> --}}
                <td><a href="{{ route('detailstok',\Hashids::encode($data->id)) }}">{{$data->nama_barang}}</a></td>
                <td align="center">{{$data->tahun_anggaran}} </td>
                {{-- <td align="center">
                  {{\DB::table('barangkeluar')->where('id_barang', $data->id)->where('aktif', 1)->sum('jumlah_keluar')}}
                </td> --}}
                <td align="center">{{$data->asal_brg}}</td>
                <td align="center">{{$data->jumlah_sekarang}}</td>
                <td align="center">{{$data->jumlah_awal}}</td>
                <td>{{$data->lokasi}}</td>
                <td align="center">
                  <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#edit-{{$data->id}}" data-placement="bottom" title="Edit"><i class="fa fa-edit"></i></button>
                  <a href="/stok/qrcode/{{\Hashids::encode($data->id)}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Qrcode">
                    <i class="fa fa-qrcode"></i></a>
                  <a href="{{ route('deletestok', \Hashids::encode($data->id))}}" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Delete" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="fa fa-trash"></i></a>

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


                      <form action="{{ route('updatestok', $data->id) }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">

                          <img id="thumb_preview_2" src="{{ !empty($data->image) ? asset('stokbarang/'.$data->image) : asset('image/datakom-logo.jpeg') }}" alt="your image" height="150px" />
                        </div>

                        <div class="form-group">
                          <label>Foto Barang</label>
                          <input type="file" accept="image/*" class="form-control" name="image" id="thumb_image_2">
                        </div>

                        <div class="form-group">
                          <label>Tahun Anggaran</label>
                          <select class="form-control" name="tahun">
                            <option value="{{$data->tahun_anggaran}}">{{$data->tahun_anggaran}}</option>
                            <option value="">-------</option>
                            @for($year=2014; $year <= date('Y'); $year++) <option value="{{$year}}">{{$year}}</option>
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

      </div>
    </div>

    {{-- <div class="form-group" >
      <label>Tahun Anggaran</label>
      <select class="form-control" name="tahun" id="table-filter">
        @for($year=2014; $year <= date('Y'); $year++)
          <option value="{{$year}}">{{$year}}</option>
    @endfor
    </select>
  </div> --}}




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
  $(document).ready(function(){
    $('a[data-toggle="tab"]').on('show.affectedDiv.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
    });
    var activeTab = localStorage.getItem('activeTab');
    if(activeTab){
        $('#myTab a[href="' + activeTab + '"]').tab('show');
    }
});
  

  function confirmDelete(event, url) {
    event.preventDefault();
    Swal.fire({
      title: 'Anda yakin ingin menghapus data ini?',
      text: "Data yang dihapus tidak dapat dikembalikan!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = url;
      }
    });
    }


  $(document).ready(function() {
    var t = $('#table-stok-1').DataTable({
      "initComplete": function() {
        this.api().columns([3]).every(function() {
          var column = this;
          var select = $('<select><option value=""></option></select>')
            .appendTo($(column.header()))
            .on('change', function() {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );

              column
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
            });

          column.data().unique().sort().each(function(d, j) {
            select.append('<option value="' + d + '">' + d + '</option>')
          });
        });
      },
      "columnDefs": [
        {
          responsive: true,
                processing: true,
                serverSide: true,
          width: "2%",
          searchable: false,
            // "orderable": false,
          targets: 0
        },
        { width: "1%", targets: 1 },
        { width: "20%", targets: 2 },
        { width: "5%", targets: 3 },
        { width: "1%", targets: 4 },
        { width: "1%", targets: 5 },
        { width: "1%", targets: 6 },
        { width: "1%", targets: 7 },
        { width: "1%", targets: 8 },
        { width: "25%", targets: 9 }
        // { width: "10%", targets: 10 }
        // { width: "10%", targets: 11 }
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



    $('#table-filter').on('change', function() {
      t.search(this.value).draw();
    });

  });
</script>

<script>
  $(document).ready(function() {
    var t = $('#table-stok-2').DataTable({
      "initComplete": function() {
        this.api().columns([2]).every(function() {
          var column = this;
          var select = $('<select><option value=""></option></select>')
            .appendTo($(column.header()))
            .on('change', function() {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );

              column
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
            });

          column.data().unique().sort().each(function(d, j) {
            select.append('<option value="' + d + '">' + d + '</option>')
          });
        });
      },
      "columnDefs": [
        {
          responsive:true,
        }
        // {
        //   width: "5%",
        //   searchable: false,
        //     // "orderable": false,
        //   targets: 0
        // },
        // { width: "25%", targets: 1 },
        // { width: "10%", targets: 2 },
        // { width: "10%", targets: 3 },
        // { width: "10%", targets: 4 },
        // { width: "10%", targets: 5 },
        // { width: "20%", targets: 6 },
        // { width: "10%", targets: 7 }
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



    $('#table-filter').on('change', function() {
      t.search(this.value).draw();
    });

    // #thumb_image to change preview image
    $('#thumb_image').change(function() {
      var reader = new FileReader();
      reader.onload = (e) => {
        $('#thumb_preview').attr('src', e.target.result);
      }
      reader.readAsDataURL(this.files[0]);
    });

    $('#thumb_image_2').change(function() {
      var reader = new FileReader();
      reader.onload = (e) => {
        $('#thumb_preview_2').attr('src', e.target.result);
      }
      reader.readAsDataURL(this.files[0]);
    });
  });
</script>
<script>
  $(document).ready(function() {
    var t = $('#table-stok-3').DataTable({
      "initComplete": function() {
        this.api().columns([2]).every(function() {
          var column = this;
          var select = $('<select><option value=""></option></select>')
            .appendTo($(column.header()))
            .on('change', function() {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );

              column
                .search(val ? '^' + val + '$' : '', true, false)
                .draw();
            });

          column.data().unique().sort().each(function(d, j) {
            select.append('<option value="' + d + '">' + d + '</option>')
          });
        });
      },
      "columnDefs": [
        // {
        //   width: "5%",
        //   searchable: false,
        //     // "orderable": false,
        //   targets: 0
        // },
        // { width: "25%", targets: 1 },
        // { width: "10%", targets: 2 },
        // { width: "10%", targets: 3 },
        // { width: "10%", targets: 4 },
        // { width: "10%", targets: 5 },
        // { width: "20%", targets: 6 },
        // { width: "10%", targets: 7 }
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



    $('#table-filter').on('change', function() {
      t.search(this.value).draw();
    });

    // #thumb_image to change preview image
    $('#thumb_image').change(function() {
      var reader = new FileReader();
      reader.onload = (e) => {
        $('#thumb_preview').attr('src', e.target.result);
      }
      reader.readAsDataURL(this.files[0]);
    });

    $('#thumb_image_2').change(function() {
      var reader = new FileReader();
      reader.onload = (e) => {
        $('#thumb_preview_2').attr('src', e.target.result);
      }
      reader.readAsDataURL(this.files[0]);
    });
  });
</script>
@endpush