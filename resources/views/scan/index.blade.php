

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QR Code Scanner</title>
{{-- <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script> --}}
<script src="{{ asset('js/camerahtml5.js') }}" type="text/javascript"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('addon/ionicons.min.css')}}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{ asset('plugin/ion/css/ionicons.min.css')}}">

    {{-- DataTables --}}
    <link rel="stylesheet" href="{{ asset('css/scan.css') }}">
    
    
</head>

<body>
  
  <div class="card-body" >
    <center>
        <div id="reader" class="solid"></div>
    </center>
    
</div>


<div class="table-responsive">
  <div class="card-body">
    <table class="table table-bordered" id="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>KIB</th>
                <th>Serial Number</th>
                <th>Nama Barang</th>
                <th>Tahun Anggaran</th>
                {{-- <th>Serial Number</th>
                <th>Status</th>
                <th>User Log Input</th>
                <th>Aksi</th> --}}
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
</div>

</body>
</html>

<script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
{{-- <!-- DataTables -->
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script> --}}
<script src="{{ asset('lte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('lte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('js/sweetalert2.all.js') }}"></script>


{{-- datatble --}}
<script type="text/javascript" src="{{ asset('plugin/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{asset('vendor/izitoast/dist/js/iziToast.min.js') }}" type="text/javascript"></script>
<script src="{{asset('lte/plugins/fancybox/dist/jquery.fancybox.min.js')}}"></script>
<script>
  
  $(document).ready(function () {
  const table = $('#data-table').DataTable({
    columnDefs: [
      { targets: 0, data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { targets: 1, data: 'kib' },
      { targets: 2, data: 'serial_number' },
      { targets: 3, data: 'nama_barang' },
      {targets: 4, data: 'tahun_anggaran'}
    ],
  });

  
  function updateTable(data) {
    table.clear(); // Clear existing data

    if (Array.isArray(data)) {
      table.rows.add(data); // Add new rows if data is an array
    } else {
      table.row.add(data); // Add a single row if data is an object
    }

    table.draw(); // Redraw the table with new data
  }

  // Define the onScanSuccess function
  function onScanSuccess(decodedText, decodedResult) {
    if (ajaxInProgress) {
      return;
    }

    ajaxInProgress = true;

    $.ajax({
      url: 'api/barangmasuk?barcode=' + decodedText,
      type: 'GET',
      success: function (response) {
        console.log(response);

        if (response && response.data && Array.isArray(response.data)) {
          const matchingData = response.data.find(item => item.id_qr === decodedText);
          if (matchingData) {
            updateTable(matchingData);
            Swal.fire({
              icon: 'success',
              title: 'Barcode Ditemukan!',
            });
          } else {
            const matchingDataByIdQrs = response.data.filter(item => item.id_qrs === decodedText);
            if (matchingDataByIdQrs.length > 0) {
              updateTable(matchingDataByIdQrs);
              matchingDataByIdQrs.forEach(data => {
                console.log(data);
              });
              Swal.fire({
                icon: 'success',
                title: 'Barcode Ditemukan!',
              });
            } else {
              console.error("No matching item found for ID:", decodedText);
              Swal.fire({
                icon: 'error',
                title: 'Barcode Tidak Ditemukan!',
              });
            }
          }
        } else {
          console.error("Invalid response format:", response);
        }

        ajaxInProgress = false; 
      },
      error: function (xhr, status, error) {
        console.error(error); 
        ajaxInProgress = false; 
      }
    });
  }

  let ajaxInProgress = false;
  
  // Initialize your QR code scanner and set the onScanSuccess callback
  let isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

let qrboxConfig;
if (isMobile) {
    qrboxConfig = { width: 200, height: 200 };
} else {
    qrboxConfig = { width: 250, height: 250 };
}

let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    { fps: 200, qrbox: qrboxConfig, disableFlip: false },
    /* verbose= */ false
);
html5QrcodeScanner.render(onScanSuccess);
});


</script>


