@extends('layouts.lteapp')

@section('title-page')
<title>Data Barang</title>

@endsection

@section('content')

<div class="card card-default">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="m-0 font-weight-bold text-dark">QRCode</h5>
    <a href="#" onclick="history.back();" class="btn btn-warning ml-auto">Kembali</a>
</div>
  
  <div class="card-body text-center">
    <table id="example" border="1" cellpadding="5" cellspacing="0" style="margin: 0 auto;">
      <tbody>
        <tr>
          <td rowspan="2">
            <center><img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->errorCorrection('H')->generate($qrcode->id_qr)) !!} "></center>
            <center><p>{{$qrcode->id_qr}}</p></center>
          </td>
          <td style="text-align: center;">
            <h3>{{$qrcode->nama_barang}}</h3>
          </td>
          <td colspan="2" rowspan="2">
            <img src="{{ asset('image/ptsp.png') }}" width="45" height="150">
          </td>
        </tr>
        <tr>
          <td>
            <center>PUSDATIN DPMPTSP PROVINSI JAKARTA</center>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  
</div>

<div class="d-flex justify-content-center">
<input type="button" value="Print" class="btn btn-primary btn" onclick="printDiv('example');" />
</div>

@endsection


<script>
function printDiv(divName) {
    var printContents = document.getElementById(divName).outerHTML;
    var originalContents = document.body.innerHTML;
    var printWindow = window.open('', '_blank');
    printWindow.document.open();
    printWindow.document.write('<html><head><title>Print</title></head><body>' + printContents + '</body></html>');
    printWindow.document.close();
    printWindow.onload = function() {
        printWindow.focus();
        printWindow.print();
    };
}
</script>