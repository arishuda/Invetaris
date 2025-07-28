@extends('layouts.appuser')


@push('styles')
<style>
#signature{
 width: 400px; height: 400px;
 border: 1px solid black;
}
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">FORM BERITA ACARA - {{$data->nama_barang}}

                  <div class="text-right">
                   <!-- {{-- <span id="copyTarget2">{{ url('/beritaacara/user/'.$data->id) }}</span>  --}} -->
                    <!-- {{-- <button id="copyButton2" class="btn btn-success btn-sm">Copy</button> --}} -->

                    <!-- {{-- <button class="btn" data-clipboard-text="{!! url('/beritaacara/user/'.$data->id) !!}">
                      Copy to clipboard
                    </button> --}} -->
                    {{-- <a href="{{route('userba', $data->id)}}" class="btn btn-primary btn-sm" target="_blank">Link to User</a> --}}
                    <a href="{{route('outputba', $data->id)}}" class="btn btn-primary btn-sm" target="_blank">Lihat Berita Acara</a>
                    <a href="{{route('downloadba', $data->id)}}" class="btn btn-success btn-sm" target="_blank">Download Berita Acara</a>
                  </div>
                </div>


                <div class="card-body">

                  <form action="{{route('updateba', $data->id)}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <!-- Target -->
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>Link Tanda Tangan</label>
                          <input type="text" class="form-control" value="{{url('/beritaacara/user/'.\Crypt::encryptString($data->id))}}" id="myInput">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>&nbsp;</label>
                          <a class="btn btn-block btn-success" onclick="myFunction()">Copy to Clipboard</i></a>
                        </div>
                      </div>
                    </div>

                    <script type="text/javascript">
                    function myFunction() {
                      /* Get the text field */
                      var copyText = document.getElementById("myInput");

                      /* Select the text field */
                      copyText.select();

                      /* Copy the text inside the text field */
                      document.execCommand("copy");

                      /* Alert the copied text */
                      alert("Copy Text: " + copyText.value);
                      }
                    </script>


                    <input type="hidden" name="id_barangkeluar" value={{$data->id_barangkeluar}}>
                    <input type="hidden" name="id_barang" value={{$data->id_barang}}>

                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>Tanggal Berita Acara</label>
                          <input class="form-control" value="{{$data->tanggal_ba}}" id="date-ba" name="tanggal_ba" placeholder="yyyy-mm-dd" type="text">
                          {{-- <input type="date" class="form-control" name="tanggal_ba" value="{{$data->tanggal_ba}}"> --}}
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>Nomor BA</label>
                          <input type="text" class="form-control" name="nomor" value="{{$data->nomor}}">
                        </div>
                      </div>
                    </div>


                    <hr>

                    <h4>Data Pihak Kesatu</h4>

                    <hr>

                    <div class="form-group">
                        <label>Nama Pihak kesatu</label>
                        <input type="text" class="form-control" name="nama_p1" value="{{$data->nama_p1}}">
                    </div>

                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>NIP</label>
                          <input type="text" class="form-control" name="nip_p1" value="{{$data->nip_p1}}">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label>NRK</label>
                          <input type="text" class="form-control" name="nrk_p1" value="{{$data->nrk_p1}}">
                        </div>
                      </div>
                    </div>

                    {{-- <div class="form-group">
                        <label>NIP/NRK</label>
                        <input type="text" class="form-control" name="nipnrk_p1" value="{{$data->nipnrk_p1}}">
                    </div> --}}

                    <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" class="form-control" name="jabatan_p1" value="{{$data->jabatan_p1}}">
                    </div>

                    <br>

                    <hr>

                    <h4>Data Pihak Kedua</h4>

                    <hr>

                    <div class="form-group">
                        <label>Nama Pihak Kedua</label>
                        <input type="text" class="form-control" name="nama_p2" value="{{$data->nama_p2}}">
                    </div>

                    <div class="form-group">
                      <!-- <label>Select</label> -->
                      
                    </div>
      

                    <div class="row">
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label>Pengambil</label>
                          <select id="jenisinput" class="form-control">
                            <option id='0' value="">- Pilih -</option>
                            <option id='1' value="1">ASN</option>
                            <option id='2' value="2">NON-ASN</option>
                          </select>
                        </div>
                      </div>

                        <div class="col-sm-4" id="asn-1" style="display:none;">
                          <div class="form-group">
                            <label>NIP</label>
                            <input type="text" class="form-control" name="nip_p2" value="{{$data->nip_p2}}">
                          </div>
                        </div>
                        <div class="col-sm-4" id="asn-2" style="display:none;">
                          <div class="form-group">
                            <label>NRK</label>
                            <input type="text" class="form-control" name="nrk_p2" value="{{$data->nrk_p2}}">
                          </div>
                        </div>

                        <div class="col-sm-8" id="nonasn" style="display:none;">
                          <div class="form-group">
                            <label>NIK</label>
                            <input type="text" class="form-control" name="nik_p2" value="{{$data->nik_p2}}">
                          </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label>Lokasi Kerja</label>
                        <input type="text" class="form-control" name="lokasikerja" value="{{$data->lokasikerja}}">
                    </div>

                    <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" class="form-control" name="jabatan_p2" value="{{$data->jabatan_p2}}">
                    </div>

                    <br>

                    <hr>

                    <h4>Data Serah Terima Barang</h4>

                    <hr>

                    <div class="form-group">
                        <label>Jenis Barang/Nama Barang</label>
                        <input type="text" class="form-control" value="{{$data->nama_barang}}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Wilayah</label>
                        <input type="text" class="form-control" name="wilayah" value="{{$data->wilayah}}">
                    </div>

                    <div class="form-group">
                        <label>Nomor Serial Barang <i>(Serial Number)</i></label>
                        <input type="text" class="form-control" name="serialnumber" value="{{$data->serialnumber}}">
                    </div>

                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="text" class="form-control" name="jumlah" value="{{$data->JK}}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Foto Barang</label> <br>
                        @if(empty($data->foto))
                          <img id="foto" width="350"/> <br>
                        @else
                          <img id="foto" src="{{ asset('fotobarang/'.$data->foto) }}" width="350"/> <br>
                        @endif
                        <input id="fotos" type="file" class="form-control" name="foto" style="margin-top: 20px;" value="{{$data->foto}}">
                    </div>


                    <br>


                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <td align="center" width="30%">Tanda Tangan Pihak Kesatu</td>
                                <td align="center" width="30%">Tanda Tangan Pihak Kedua</td>
                                <td align="center" width="30%">Tanda Tangan Satpel Prasasti</td>
                            </tr>
                            <tr>
                                <td>
                                    @if(empty($data->ttd_p1))
                                    <center>
                                      Kosong
                                    </center>
                                    @else
                                    <center>
                                        <img src="data:image/png;base64,{{$data->ttd_p1}}" width="250px" />
                                    </center>
                                    @endif
                                </td>


                                {{-- ------------------------------------------------------------------------ --}}

                                <td>
                                    @if(empty($data->ttd_p2))
                                    <center>
                                      Kosong
                                    </center>
                                    @else
                                    <center>
                                        <img src="data:image/png;base64,{{$data->ttd_p2}}" width="250px" />
                                    </center>
                                    @endif
                                </td>


                                {{-- ------------------------------------------------------------------------ --}}

                                <td>
                                    @if(empty($data->ttd_kasatpel))
                                    <center>
                                      Kosong
                                    </center>
                                    @else
                                    <center>
                                        <img src="data:image/png;base64,{{$data->ttd_kasatpel}}" width="250px" />
                                    </center>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>


                    {{-- <div class="form-group">
                      <label>Tanda Tangan</label>
                      <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#ttd">
                        Klik Disini untuk Tanda Tangan
                      </button>
                    </div> --}}

                    {{-- <div class="modal fade" id="ttd" tabindex="-1" role="dialog" aria-labelledby="ttd" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="ttd">Digital Signature</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <center>
                            <center>
                            <div id="signature" style=''>
                             <canvas id="signature-pad" class="signature-pad" width="400px" height="400px"></canvas>
                            </div>
                          </center>
                          </center>

                          <br/>
                        </div>
                        <div class="modal-footer">

                          <button type="button" id='hapus' class="btn btn-danger button clear" data-action="clear">Clear</button>
                          <input type='button' id='click' value='Apply' class="btn btn-secondary" data-dismiss="modal">

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <textarea id='output' name="ttd" rows="1" class="form-control" readonly="" required></textarea><br/>
                    <center>
                    <img src='' id='sign_prev' width="250px" style="display: none;" />
                  </center>
                  </div> --}}



                    <button type="submit" name="button" class="btn btn-block btn-success">Submit</button>
                  </form>

                </div>
            </div>
        </div>
    </div>
</div>



</div>
{{-- <!-- <script src="{{asset('plugin/spad/docs/js/signature_pad.umd.js')}}"></script>
<script src="{{asset('plugin/ttduser.js')}}"></script> --> --}}
@endsection



@push('scripts')
{{-- <script src="{{asset('addon/jquery.min.js')}}"></script> --}}
<!-- <script src="{{asset('addon/clipboard.min.js')}}"></script> -->
<!-- <script src="{{asset('addon/zero/dist/ZeroClipboard.js')}}"></script> -->
<!-- <script src="{{asset('addon/zero/dist/main.js')}}"></script> -->
<!-- {{-- <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> --}}
{{-- <script src="{{asset('plugin/spad/docs/js/signature_pad.umd.js')}}"></script> --}} -->
{{-- <!-- <script src="{{asset('plugin/ttduser.js')}}"></script> --> --}}

<!-- <script type="text/javascript">
$( document ).ready(function() {
  var clipboard = new Clipboard('.clipboard');

  clipboard.on('success', function(e) {
    $(e.trigger).text("Copied!");
    e.clearSelection();
    setTimeout(function() {
      $(e.trigger).text("Copy");
    }, 2500);
  });

  clipboard.on('error', function(e) {
    $(e.trigger).text("Can't in Safari");
    setTimeout(function() {
      $(e.trigger).text("Copy");
    }, 2500);
  });

});
</script> -->


<script type="text/javascript">

  $(function () {
    document.getElementById("fotos").onchange = function () {
    var reader = new FileReader();

    reader.onload = function (e) {
    // get loaded data and render thumbnail.
    document.getElementById("foto").src = e.target.result;
    };

    // read the image file as a data URL.
    reader.readAsDataURL(this.files[0]);
    };
  });

$(function () {
  $("#date-ba").datepicker({
        autoclose: true,
        todayHighlight: true,
        format : 'yyyy-mm-dd'
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
