@extends('layouts.b1_appuser')

@push('datepicker')
<link href="{{ asset('datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet">
@endpush

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
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Form Berita Acara  {{$data->nama_barang}}
                    <div class="text-right">
                    <a href="{{route('outputba', \Hashids::encode($data->id))}}" class="btn btn-primary btn-sm" target="_blank">Lihat Berita Acara</a>
                    <a href="{{route('downloadba', \Hashids::encode($data->id))}}" class="btn btn-success btn-sm" target="_blank">Download Berita Acara</a>
                   </div>
                </div>

                <div class="card-body">

                  <form action="{{route('userupdateba', \Hashids::encode($data->id))}}" method="post">
                    @csrf
                    
                    <input type="hidden" name="id_barangkeluar" value={{$data->id_barangkeluar}}>
                    <input type="hidden" name="id_peminjaman" value={{$data->id_peminjaman}}>
                    <input type="hidden" name="id_barang" value={{$data->id_barang}}>

                    Pada hari ini {{\TANGGAL::Hari($data->tanggal_ba)}} tanggal {{$TanggalBA}} bulan {{\TANGGAL::Bulan($data->tanggal_ba)}} tahun {{$TahunBA}} ({{date('d-m-Y', strtotime($data->tanggal_ba))}}), kami yang bertanda tangan di bawah ini :
                    {{-- <div class="form-group">
                      <label>Tanggal Berita Acara</label>
                      <input type="date" class="form-control" name="tanggal_ba" value="{{$data->tanggal_ba}}">
                    </div> --}}

                    <br>
                    <br>

                    {{-- <hr>

                    <h4>Data Pihak Kesatu</h4>

                    <hr> --}}

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Pihak Kesatu</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" value="{{$data->nama_p1}}" readonly>
                        </div>
                    </div>



                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">NIP</label>
                        <div class="col-sm-3">
                            <input type="email" class="form-control" value="{{$data->nip_p1}}" readonly>
                        </div>
                        <label class="col-sm-3 col-form-label">NRK</label>
                        <div class="col-sm-3">
                            <input type="email" class="form-control" value="{{$data->nrk_p1}}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jabatan</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" value="{{$data->jabatan_p1}}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <b>&nbsp; &nbsp; Yang Selanjutnya disebut PIHAK KESATU</b>
                        </div>
                    </div>


                    <br>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Pihak Kedua</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" value="{{$data->nama_p2}}" readonly>
                        </div>
                    </div>

                    @if($data->statuskerja_p2==1)

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">NIP</label>
                        <div class="col-sm-3">
                            <input type="email" class="form-control" value="{{$data->nip_p2}}" readonly>
                        </div>
                        <label class="col-sm-3 col-form-label">NRK</label>
                        <div class="col-sm-3">
                            <input type="email" class="form-control" value="{{$data->nrk_p2}}" readonly>
                        </div>
                    </div>

                    @elseif($data->statuskerja_p2==2)

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">NIK</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" value="{{$data->nik_p2}}" readonly>
                        </div>
                    </div>

                    @endif

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Lokasi Kerja</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" value="{{$data->lokasikerja}}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jabatan</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" value="{{$data->jabatan_p2}}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <b>&nbsp; &nbsp; Yang Selanjutnya disebut PIHAK KEDUA</b>
                        </div>
                    </div>

                    <br>

                    {{-- <hr>

                    <h4>Data Serah Terima Barang</h4>

                    <hr>     --}}
                    <p>
                        {{$data->ket1}}
                    </p>

                    <table width="100%" border="1">
                        <tr bgcolor="#f0f0f0" >
                            <th bgcolor="#f0f0f0" width="30%" style="text-align: center;" height="20px">Jenis Barang</th>
                            <th width="28%" style="text-align: center;">Wilayah</th>
                            <th width="30%" style="text-align: center;">Serial Number</th>
                            <th width="10%" style="text-align: center;">Jumlah</th>
                        </tr>
                        <tr>
                            <td height="40px" style="text-align: center;">{{$data->nama_barang}}</td>
                            <td style="text-align: center;">{{$data->wilayah}}</td>
                            <td style="text-align: center;" >{{$data->serialnumber}}</td>
                            <td style="text-align: center;" >{{$data->jumlah}}</td>
                        </tr>
                    </table>

                    <br>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Foto Barang</label>
                        <div class="col-sm-9">
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#ModalFoto">
                                Lihat Foto
                            </button>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="ModalFoto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Foto {{$data->nama_barang}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <center>
                                        @if(empty($data->foto))
                                        <img id="foto" width="350"/> <br>
                                        @else
                                        <img id="foto" src="{{ asset('fotobarang/'.$data->foto) }}" width="350"/> <br>
                                        @endif
                                    </center>
                                </div>
                                {{-- <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    @if(!empty($data->tgl_kembali))
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Pengembalian</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" value="{{$data->tgl_kembali}}" readonly>
                        </div>
                    </div>
                    @else
                    @endif


                    <br>

                    <p>
                    {{$data->ket3}}
                    </p>

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
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="{{asset('plugin/spad/docs/js/signature_pad.umd.js')}}"></script>
{{-- <!-- <script src="{{asset('plugin/ttduser.js')}}"></script> --> --}}
<script>
$(document).ready(function() {

    var signaturePad = new SignaturePad(document.getElementById('signature-pad_p1'));
    var clear = document.getElementById('hapus_p1');
    var aplbtn = document.getElementById('click_p1');

    $(aplbtn).click(function(){
    var datattd = signaturePad.toDataURL('image/png');
    var data = datattd.replace(/^data:image\/(png|jpg);base64,/, "");
    $('#output_p1').val(data);
    $("#sign_prev_p1").show();
    $("#sign_prev_p1").attr("src","data:image/png;base64,"+data);
    });

    $(clear).click(function(){
    signaturePad.clear();
    // Open image in the browser
    //window.open(data);
    });


//  ---------------------------------------

    var signaturePad2 = new SignaturePad(document.getElementById('signature-pad_p2'));
    var clear2 = document.getElementById('hapus_p2');
    var aplbtn2 = document.getElementById('click_p2');

    $(aplbtn2).click(function(){
    var datattd2 = signaturePad2.toDataURL('image/png');
    var data2 = datattd2.replace(/^data:image\/(png|jpg);base64,/, "");
    $('#output_p2').val(data2);
    $("#sign_prev_p2").show();
    $("#sign_prev_p2").attr("src","data:image/png;base64,"+data2);
    });

    $(clear2).click(function(){
    signaturePad2.clear();
    // Open image in the browser
    //window.open(data);
    });

    var signaturePad3 = new SignaturePad(document.getElementById('signature-pad_p3'));
    var clear3 = document.getElementById('hapus_p3');
    var aplbtn3 = document.getElementById('click_p3');

    $(aplbtn3).click(function(){
    var datattd3 = signaturePad3.toDataURL('image/png');
    var data3 = datattd3.replace(/^data:image\/(png|jpg);base64,/, "");
    $('#output_p3').val(data3);
    $("#sign_prev_p3").show();
    $("#sign_prev_p3").attr("src","data:image/png;base64,"+data3);
    });

    $(clear3).click(function(){
    signaturePad3.clear();
    // Open image in the browser
    //window.open(data);
    });


})
</script>
<script>
// $(document).ready(function() {
//   var signaturePad = new SignaturePad(document.getElementById('signature-pad'));
//   var clearButton = wrapper.querySelector("[data-action=clear]");
//
//   $('#click').click(function(){
//     var datattd = signaturePad.toDataURL('image/png');
//     var data = datattd.replace(/^data:image\/(png|jpg);base64,/, "");
//     $('#output').val(data);
//
//     $("#sign_prev").show();
//     $("#sign_prev").attr("src","data:image/png;base64,"+data);
//     // Open image in the browser
//     //window.open(data);
//     });
//
//     $('#hapus').click(function(){
//     signaturePad.clear();
//     // Open image in the browser
//     //window.open(data);
//   });
// })
</script>
@endpush

{{-- @push('datepicker_sc')
<script type="text/javascript" src="{{ asset('datepicker/js/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript">
    $(function () {
      $("#date-1").datepicker({
            autoclose: true,
            // todayHighlight: true,
            format : 'yyyy-mm-dd'
      });


    });

</script>
@endpush --}}
