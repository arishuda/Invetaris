@extends('layouts.appuser')


@push('styles')
<style>
  #signature {
    width: 400px;
    height: 400px;
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
            <a href="{{route('outputba', \Hashids::encode($data->id))}}" class="btn btn-primary btn-sm" target="_blank">Lihat Berita Acara</a>
            <a href="{{route('downloadba', \Hashids::encode($data->id))}}" class="btn btn-success btn-sm" target="_blank">Download Berita Acara</a>
          </div>
        </div>


        <div class="card-body">

          <form action="{{route('updateba', $data->id)}}" method="post" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="buatba" value="2">

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


            <input type="hidden" name="id_peminjaman" value={{$data->id_peminjaman}}>
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
              <select id="jenisinput" name="statuskerja_p2" class="form-control">
                @if($data->statuskerja_p2==1)
                <option id='1' value="1">ASN</option>
                @elseif($data->statuskerja_p2==2)
                <option id='2' value="2">NON-ASN</option>
                @else
                @endif
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

        <h4>Data Peminjaman Barang</h4>

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
          <input type="text" class="form-control" name="serialnumber" value="{{$data->serialnumber}}" readonly>
        </div>

       

        <div class="form-group">
          <label>Jumlah</label>
          <input type="text" class="form-control" name="jumlah" value="{{$data->JK}}" readonly>
        </div>

        <div class="form-group">
          <label>Tanggal Pengembalian</label>
          <input class="form-control" value="{{$data->tgl_kembali}}" id="date-kembali" name="tgl_kembali" placeholder="yyyy-mm-dd" type="text">
        </div>

        <div class="form-group">
          <label>Foto Barang</label> <br>
          @if(empty($data->foto))
          <img id="foto" width="350" /> <br>
          @else
          <img id="foto" src="{{ asset('fotobarang/'.$data->foto) }}" width="350" /> <br>
          @endif
          <input id="fotos" type="file" class="form-control" name="foto" style="margin-top: 20px;" value="{{$data->foto}}">
        </div>

        <h4>Form Edit Pdf</h4>

        <hr>
        <div class="form-group">
          <label>Keterangan 1</label>
          @if($data->ket1==null)
        <textarea id="ket1" name="ket1" rows="3" cols="80" class="form-control">PIHAK KESATU menyerahkan barang kepada PIHAK KEDUA, dan PIHAK KEDUA menyatakan menerima barang dari PIHAK KESATU berupa
        </textarea>
        @elseif($data->ket1)
        <textarea id="ket1" name="ket1" rows="3" cols="80"  class="form-control"> {{$data->ket1}}</textarea>
        @endif
      </div>
      <div class="form-group">
        <label>Keterangan 2</label>
        @if($data->ket2==null)
        <textarea id="ket2" name="ket2" rows="3" cols="80" class="form-control">Barang tersebut digunakan oleh PIHAK KEDUA untuk keperluan {{$data->keperluan}} dan akan menyerahkan kembali apabila sewaktu-waktu PIHAK KESATU akan menarik Barang tersebut.</textarea>
        @elseif ($data->ket2)
        <textarea id="ket2" name="ket2" rows="3" cols="80" class="form-control">{{$data->ket2}}</textarea>
        @endif
      </div>
      <div class="form-group">
        <label>Keterangan 3</label>
        @if ($data->ket3==null)
        <textarea id="ket3" name="ket3" rows="3" cols="80" class="form-control">Demikianlah Berita Acara Serah Terima Barang ini dibuat oleh kedua belah pihak, adapun barang-barang tersebut dalam keadaan baik, sejak penandatanganan Berita Acara ini, maka pemanfaatannya menjadi tanggung jawab PIHAK KEDUA sesuai ketentuan pengelolaan aset.</textarea>
        @elseif ($data->ket3)
        <textarea id="ket3" name="ket3" rows="3" cols="80" class="form-control">{{$data->ket3}}</textarea>
        @endif
      </div>

        <br>


        <div class="row">
          <div class="col-md-6" style="text-align: center;">
              Yang Menyerahkan, <br>
              PIHAK KESATU <br>

              @if(empty($data->ttd_p1))
                      <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#ttd_p1">
                          Klik Disini untuk Tanda Tangan
                      </button>

                      <br/>

                      <center>
                          <img src='' id='sign_prev_p1' width="250px" style="display: none;" />
                      </center>

                      <br>

                      {{$data->nama_p1}}
                          <br>
                      {{$data->nip_p1}}

                      <br>
                      <textarea id='output_p1' name="ttd_p1" rows="1" class="form-control" readonly="" required>
                      </textarea>

                      @else
                      <center>
                          <img src="data:image/png;base64,{{$data->ttd_p1}}" width="250px" />
                      </center>
                      <br>

                      {{$data->nama_p1}}
                          <br>
                      {{$data->nip_p1}}
                      @endif

                      <div class="modal fade" id="ttd_p1" tabindex="-1" role="dialog" aria-labelledby="ttd_p1" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="ttd_p1">Digital Signature Pihak Kesatu</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <center>
                                  <center>
                                  <div id="signature" style=''>
                                   <canvas id="signature-pad_p1" class="signature-pad_p1" width="400px" height="400px"></canvas>
                                  </div>
                                </center>
                                </center>

                                <br/>
                              </div>
                              <div class="modal-footer">

                                <button type="button" id='hapus_p1' class="btn btn-danger button clear" data-action="clear_p1">Clear</button>
                                <input type='button' id='click_p1' value='Apply' class="btn btn-secondary" data-dismiss="modal">

                              </div>
                            </div>
                          </div>
                        </div>

          </div>
          <div class="col-md-6" style="text-align: center;">
              Yang Menerima, <br>
              PIHAK KEDUA <br>

                      @if(empty($data->ttd_p2))
                      <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#ttd_p2">
                          Klik Disini untuk Tanda Tangan
                      </button>

                      <br/>

                      <center>
                          <img src='' id='sign_prev_p2' width="250px" style="display: none;" />
                      </center>

                      <br>

                      {{$data->nama_p2}}
                          <br>
                      {{$data->nip_p2}}

                      <br>

                      <textarea id='output_p2' name="ttd_p2" rows="1" class="form-control" readonly="" required>
                      </textarea>
                      @else
                      <center>
                          <img src="data:image/png;base64,{{$data->ttd_p2}}" width="250px" />
                      </center>

                      <br>

                          {{$data->nama_p2}}
                          <br>
                          @if(empty($data->nik_p2))
                              {{$data->nip_p2}}
                          @else
                              {{$data->nik_p2}}
                          @endif
                      @endif

                      <div class="modal fade" id="ttd_p2" tabindex="-1" role="dialog" aria-labelledby="ttd_p2" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="ttd_p2">Digital Signature Pihak Kedua</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <center>
                                  <center>
                                  <div id="signature" style=''>
                                   <canvas id="signature-pad_p2" class="signature-pad_p2" width="400px" height="400px"></canvas>
                                  </div>
                                </center>
                                </center>

                                <br/>
                              </div>
                              <div class="modal-footer">

                                <button type="button" id='hapus_p2' class="btn btn-danger button clear" data-action="clear_p2">Clear</button>
                                <input type='button' id='click_p2' value='Apply' class="btn btn-secondary" data-dismiss="modal">

                              </div>
                            </div>
                          </div>
                        </div>

          </div>
          
          <div class="col-md-4" style="text-align: center;"></div>
      </div>
      <br>
      <div>
          <div class="col-md-12" style="text-align: center;">
              Mengetahui, <br>
              Kepala Sub Bagian Tata Usaha <br>
              Pusdatin PMPTSP DPMPTSP DKI Jakarta <br>

              @if(empty($data->ttd_kasatpel))
                      <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#ttd_p3">
                          Klik Disini untuk Tanda Tangan
                      </button>

                      <br/>

                      <center>
                          <img src='' id='sign_prev_p3' width="250px" style="display: none;" />
                      </center>

                      <br>
                      Darmawan Apriyadi <br>
                      NIP 198504132010011023

                      <br>

                      <textarea id='output_p3' name="ttd_kasatpel" rows="1" class="form-control" readonly="" required>
                      </textarea>

                      @else
                      <center>
                          <img src="data:image/png;base64,{{$data->ttd_kasatpel}}" width="250px" />
                      </center>

                      <br>
                      Darmawan Apriyadi <br>
                      NIP 198504132010011023
                      @endif

                      <div class="modal fade" id="ttd_p3" tabindex="-1" role="dialog" aria-labelledby="ttd_p3" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="ttd_p3">Digital Signature Satpel Prasasti</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <center>
                                  <center>
                                  <div id="signature" style=''>
                                   <canvas id="signature-pad_p3" class="signature-pad_p3" width="400px" height="400px"></canvas>
                                  </div>
                                </center>
                                </center>

                                <br/>
                              </div>
                              <div class="modal-footer">

                                <button type="button" id='hapus_p3' class="btn btn-danger button clear" data-action="clear_p3">Clear</button>
                                <input type='button' id='click_p3' value='Apply' class="btn btn-secondary" data-dismiss="modal">

                              </div>
                            </div>
                          </div>
                        </div>

          </div>
      </div>

      <br>
      <br>



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
<script src="{{asset('plugin/spad/docs/js/signature_pad.umd.js')}}"></script>
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
  $(function() {
    document.getElementById("fotos").onchange = function() {
      var reader = new FileReader();

      reader.onload = function(e) {
        // get loaded data and render thumbnail.
        document.getElementById("foto").src = e.target.result;
      };

      // read the image file as a data URL.
      reader.readAsDataURL(this.files[0]);
    };
  });

  $(function() {
    $("#date-ba").datepicker({
      autoclose: true,
      todayHighlight: true,
      format: 'yyyy-mm-dd'
    });
  });

  $(function() {
    $("#date-kembali").datepicker({
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

  $(document).ready(function() {

    var signaturePad = new SignaturePad(document.getElementById('signature-pad_p1'));
    var clear = document.getElementById('hapus_p1');
    var aplbtn = document.getElementById('click_p1');

    $(aplbtn).click(function() {
      var datattd = signaturePad.toDataURL('image/png');
      var data = datattd.replace(/^data:image\/(png|jpg);base64,/, "");
      $('#output_p1').val(data);
      $("#sign_prev_p1").show();
      $("#sign_prev_p1").attr("src", "data:image/png;base64," + data);
    });

    $(clear).click(function() {
      signaturePad.clear();
      // Open image in the browser
      //window.open(data);
    });


    //  ---------------------------------------

    var signaturePad2 = new SignaturePad(document.getElementById('signature-pad_p2'));
    var clear2 = document.getElementById('hapus_p2');
    var aplbtn2 = document.getElementById('click_p2');

    $(aplbtn2).click(function() {
      var datattd2 = signaturePad2.toDataURL('image/png');
      var data2 = datattd2.replace(/^data:image\/(png|jpg);base64,/, "");
      $('#output_p2').val(data2);
      $("#sign_prev_p2").show();
      $("#sign_prev_p2").attr("src", "data:image/png;base64," + data2);
    });

    $(clear2).click(function() {
      signaturePad2.clear();
      // Open image in the browser
      //window.open(data);
    });

    var signaturePad3 = new SignaturePad(document.getElementById('signature-pad_p3'));
    var clear3 = document.getElementById('hapus_p3');
    var aplbtn3 = document.getElementById('click_p3');

    $(aplbtn3).click(function() {
      var datattd3 = signaturePad3.toDataURL('image/png');
      var data3 = datattd3.replace(/^data:image\/(png|jpg);base64,/, "");
      $('#output_p3').val(data3);
      $("#sign_prev_p3").show();
      $("#sign_prev_p3").attr("src", "data:image/png;base64," + data3);
    });

    $(clear3).click(function() {
      signaturePad3.clear();
      // Open image in the browser
      //window.open(data);
    });


  })
</script>
@endpush