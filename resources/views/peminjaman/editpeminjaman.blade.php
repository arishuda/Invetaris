@extends('layouts.lteapp')

@section('title-page')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>Peminjaman</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{route('home')}}">Beranda</a></li>
      <li class="breadcrumb-item active">Peminjaman</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="card card-danger">
  <div class="card-header">
    <!-- <h3 class="card-title">Title</h3> -->
	
    @if($data->buatba==0)
		<div class="btn-group" role="group">
		<button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			Berita Acara
		</button>
		<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
			<!-- <a class="dropdown-item text-dark" href="{{route('bast', $data->BA_ID)}}">BA Serah Terima</a> -->
			<a class="dropdown-item text-dark" href="{{route('bapp', \Hashids::encode($data->BA_ID))}}">BA Pinjam Pakai</a>
			<a class="dropdown-item text-dark" href="#" data-toggle="modal" data-target="#upload-{{$data->id}}" data-placement="bottom" title="Edit">Upload BA</a>
			
		</div>
		</div>
		@elseif($data->buatba==2)
		{{-- PINJAM PAKAI --}}
		<div class="btn-group" role="group">
		<button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			Aksi
		</button>
		<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
			@if (Auth::user()->level == 'admin' || Auth::user()->level == 'superadmin')
			<a class="dropdown-item text-dark" href="{{route('bapp', \Hashids::encode($data->BA_ID))}}">Lihat BA</a>
			@else
			<a href="{{route('userba', \Crypt::encryptString($data->BA_ID))}}" class="dropdown-item text-dark">Lihat BA</a>
			@endif
			{{-- =-------------------------------- --}}
			<hr>
		</div>
		</div>
		@elseif($data->buatba==3) 
		{{-- //UPLOADAN --}}
		<div class="btn-group" role="group">
		<button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			Aksi
		</button>
		<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
			{{-- <a class="dropdown-item" href="#" data-toggle="modal" data-target="#BA-{{$data->id}}" data-placement="bottom" title="BA">Lihat BA</a> --}}
			<a class="dropdown-item text-dark" href="{{url('uploadba/'.$data->NAMAFILE)}}" target="_blank">Lihat BA</a>
			{{-- =-------------------------------- --}}
			<hr>
			
			
		</div>
		</div>
	@endif
		<div class="card-tools">
			@if($data->aktif==1)
			<a class="btn btn-warning btn-sm" href="{{route('peminjaman')}}">Kembali</a>
			@elseif($data->aktif==2) 
			<a class="btn btn-warning btn-sm" href="{{route('keranjangpeminjaman')}}">Kembali</a>
			@endif
		</div>
	
</div>
  <div class="card-body">
	<form action="{{route('updatepeminjaman', $data->id)}}" method="post">
		@csrf
		
		<input type="hidden" name="id_barang" value="{{$data->id_barang}}">

		<div class="form-group">
		  <label>Nama Barang</label>
		  <input type="text" class="form-control" name="nama_barang" value="{{$data->nama_barang}}" readonly>
		</div>

		<div class="row">
		  <div class="col-sm-6">
			<div class="form-group">
			  <label>Jumlah Barang Dipinjam</label>
			  <input type="text" class="form-control" name="jumlah_pinjam" value="{{$data->jumlah_pinjam}} {{$data->satuan}}" readonly>
			</div>
		  </div>
		  <div class="col-sm-6">
			<div class="form-group">
			  <label>Tanggal Keluar</label>
			  <input type="text" class="form-control" name="tanggal_pinjam" value="{{$data->tanggal_pinjam}}" readonly>
			</div>
		  </div>
		</div>

		<div class="form-group">
		  <label>Di ambil oleh</label>
		  <input type="text" class="form-control" name="dipinjam" value="{{$data->dipinjam}}" readonly>
		</div>
		
		<div class="form-group">
			<label>Serial Numbers</label>
			<input type="text" class="form-control" name="serial_number" value="{{ $data->serialnumber }}" readonly>
		</div>

		<div class="form-group">
		  <label>Keperluan</label>
		  <input type="text" class="form-control" name="keperluan" value="{{$data->keperluan}}">
		</div>


		<button type="submit" name="button" class="btn btn-success">Update</button>
	  </form>


	
  </div>
  <!-- /.card-body -->
  {{-- <div class="card-footer">
    Footer
  </div> --}}
  <!-- /.card-footer-->

  <div class="modal fade" id="upload-{{$data->id}}">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title">Upload Berita Acara</h4>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>


		<div class="modal-body">


				<form action="{{route('uploadBA')}}" method="post" enctype="multipart/form-data">
				  @csrf

				  <input type="hidden" name="id_peminjaman" value="{{$data->id}}">
				  
				  <input type="hidden" name="id_barang" value="{{$data->id_barang}}">

				  <div class="form-group">
					<label>Upload File</label>
					<input type="file" class="form-control" name="dokumen">
				  </div>

				  {{-- <div class="input-group mb-3">
					<div class="custom-file">
					  <input type="file" class="custom-file-input" id="inputGroupFile02" name="filename">
					  <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
					</div>
					{{-- <div class="input-group-append">
					  <span class="input-group-text" id="">Upload</span>
					</div> 
				  </div> --}}

				  {{-- <div class="row">
					<div class="col-sm-6">
					  <div class="form-group">
						<label>Jumlah Barang Keluar</label>
						<input type="text" class="form-control" name="jumlah_keluar" value="{{$data->jumlah_keluar}} {{$data->satuan}}" readonly>
					  </div>
					</div>
					<div class="col-sm-6">
					  <div class="form-group">
						<label>Tanggal Keluar</label>
						<input type="text" class="form-control" name="tanggal_keluar" value="{{$data->tanggal_keluar}}" readonly>
					  </div>
					</div>
				  </div>

				  <div class="form-group">
					<label>Di ambil oleh</label>
					<input type="text" class="form-control" name="diambil" value="{{$data->diambil}}" readonly>
				  </div>

				  <div class="form-group">
					<label>Keperluan</label>
					<input type="text" class="form-control" name="keperluan" value="{{$data->keperluan}}">
				  </div> --}}


				  <button type="submit" name="button" class="btn btn-success">Upload</button>
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


@endsection


@push('scripts')

@endpush
