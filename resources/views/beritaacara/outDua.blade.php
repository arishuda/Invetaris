<table width="100%" border="0">
    <tr>
        <td align="center" width="45%"><u>{{$dataBA->nama_p2}}</u> </td>
        <td width="8%"></td>
        <td align="center" width="45%"><u>{{$dataBA->nama_p1}}</u> </td>
    </tr>
    <tr>
        @if($dataBA->statuskerja_p2==1)
        <td align="center">NIP {{$dataBA->nip_p2}}</td>
        @elseif($dataBA->statuskerja_p2==2)
        <td align="center">NIK {{$dataBA->nik_p2}}</td>
        @endif
        <td></td>
        {{-- <td align="center">NIP {{$dataBA->nip_p1}}</td> --}}
    </tr>
    <tr>
      <td colspan="3" align="center"></td>
    </tr>
    <tr>
        <td colspan="3" align="center">Mengetahui,</td>
      </tr>
    <tr>
        <td colspan="3" align="center">Kepala Sub Bagian Tata Usaha</td>
    </tr>
    <tr>
        <td colspan="3" align="center">Pusdatin PMPTSP DPMPTSP DKI Jakarta</td>
    </tr>
  </table>
