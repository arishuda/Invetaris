<style type="text/css">
    #trouble {
      padding:10px;
    }
</style>

<table width="100%" border="0">
  <tr>
    <td width="25%"></td>
    <td width="3%"></td>
    <td width="70%"></td>
  </tr>
  <tr>
    <td colspan="3" style="width:100%" align="center"><b><u>BERITA ACARA SERAH TERIMA BARANG</u></b></td>
  </tr>
  <tr>
    <td width="25%"></td>
    <td width="3%"></td>
    <td align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nomor : {{$dataBA->nomor}}</td>
  </tr>
  <tr>
    <td colspan="3" align="center"></td>
  </tr>
  <tr>
    <td colspan="3" style="text-align: justify;">Pada hari ini {{\TANGGAL::Hari($dataBA->tanggal_ba)}} tanggal {{$TanggalBA}} bulan {{\TANGGAL::Bulan($dataBA->tanggal_ba)}} tahun {{$TahunBA}} ({{date('d-m-Y', strtotime($dataBA->tanggal_ba))}}), Kami yang bertanda tangan di bawah ini :
    </td>
  </tr>

  <tr>
    <td colspan="3" align="center"></td>
  </tr>

  <tr>
    <td>Nama</td>
    <td align="center">:</td>
    <td>{{$dataBA->nama_p1}}</td>
  </tr>

  {{-- <tr>
    <td>NIP/NRK</td>
    <td align="center">:</td>
    <td>{{$dataBA->nip_p1}} / {{$dataBA->nrk_p1}} </td>
  </tr> --}}

  <tr>
    <td>Jabatan</td>
    <td align="center">:</td>
    <td>{{$dataBA->jabatan_p1}}</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    {{-- <td>Dinas Penanaman Modal dan PTSP Provinsi DKI Jakarta</td> --}}
  </tr>
  <tr>
    <td colspan="3"></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td>Selanjutnya dalam Berita Acara ini disebut sebagai PIHAK KESATU</td>
  </tr>

  <tr>
    <td colspan="3"></td>
  </tr>

  <tr>
    <td>Nama</td>
    <td align="center">:</td>
    <td>{{$dataBA->nama_p2}}</td>
  </tr>

  @if($dataBA->statuskerja_p2==1)
  <tr>
    <td>NIP/NRK</td>
    <td align="center">:</td>
    <td>{{$dataBA->nip_p2}} / {{$dataBA->nrk_p2}}</td>
  </tr>
  @elseif($dataBA->statuskerja_p2==2)
  <tr>
    <td>NIK</td>
    <td align="center">:</td>
    <td>{{$dataBA->nik_p2}}</td>
  </tr>
  @endif

  <tr>
    <td>Jabatan</td>
    <td align="center">:</td>
    <td>{{$dataBA->jabatan_p2}}</td>
  </tr>

  <tr>
    <td></td>
    <td align="center"></td>
    <td>{{$dataBA->lokasikerja}}</td>
  </tr>



  <tr>
    <td colspan="3"></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td>Selanjutnya dalam Berita Acara ini disebut sebagai PIHAK KEDUA</td>
  </tr>

  <tr>
    <td colspan="3"></td>
  </tr>

  <tr>
    <td colspan="3" style="text-align: justify;">{{$dataBA->ket1}} {{$dataBA->jumlah}} ({{$JumlahBA}}) {{$dataBA->satuan}} {{$dataBA->nama_barang}} dengan spesifikasi sebagai berikut
    
    </td>
  </tr>
</table>

<br>
<br>


<table width="100%" border="1">
  <tr bgcolor="#f0f0f0" >
    <th bgcolor="#f0f0f0" width="7%" align="center" height="20px">No.</th>
    <th width="33%" align="center">Nama Barang</th>
    <th width="28%" align="center">Serial Number</th>
    <th width="30%" align="center">Lokasi</th>
  </tr>
  <tr>
    <td height="40px" style="text-align: center;">1.</td>
    <td align="center" style="text-align: justify;">{{$dataBA->nama_barang}}</td>
    @if(($dataBA->id_peminjaman))
    <td align="center" style="text-align: justify;">{{$dataBA->serialnumber}}</td>
    @elseif(($dataBA->id_barangkeluar))
    <td align="center" style="text-align: justify;">{{ implode(', ', $serialNumbers) }}</td>
    <td align="center" style="text-align: justify;">{{$dataBA->lokasikerja}}</td>
    @endif
  </tr>
</table>
<br>
<br>

<table width="100%" border="0">
  <tr>
    @if(empty($dataBA->tgl_kembali))
    <td colspan="3" width="98%" style="text-align: justify;">{{$dataBA->ket2}}</td>
    @else
    <td colspan="3" width="98%" style="text-align: justify;">Barang tersebut digunakan oleh PIHAK KEDUA untuk keperluan {{$dataBA->keperluan}} dan akan menyerahkan kembali paling lambat pada tanggal {{$dataBA->tgl_kembali}}. Selain itu, PIHAK KEDUA akan menyerahkan kembali apabila sewaktu-waktu PIHAK KESATU akan menarik Barang tersebut.</td>
    @endif
  </tr>
</table>

<br>
<br>

<table width="100%" border="0">
  <tr>
    <td colspan="3" width="98%" style="text-align: justify;">{{$dataBA->ket3}}
    </td>
  </tr>
</table>

<br>
<br>

<table width="100%" border="0">
  <tr>
    <td width="45%" align="center">Yang Menerima,</td>
    <td width="8%"></td>
    <td width="45%" align="center">Yang Menyerahkan,</td>
  </tr>
  <tr>
    <td align="center">{{$dataBA->jabatan_p1}}</td>
    <td></td>
    <td align="center">{{$dataBA->jabatan_p2}}</td>
  </tr>
  <tr>
    <td align="center">{{$dataBA->lokasikerja}}</td>
    <td></td>
    {{-- <td align="center">DPMPTSP Provinsi DKI Jakarta</td> --}}
  </tr>
  <tr>
    <td align="center">PIHAK KEDUA</td>
    <td></td>
    <td align="center">PIHAK KESATU</td>
  </tr>
</table>


