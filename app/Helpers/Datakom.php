<?php
  
if (!function_exists('uploadFile')) {
  function uploadFile($request, $directory){
    $file     = $request;
    $dt       = \Carbon\Carbon::now();
    $acak     = $file->getClientOriginalExtension();
    $fileName = rand(11111,99999).'-'.$dt->format('Y-m-d-H-i-s').'.'.$acak;
    $file->move($directory, $fileName);
    return $fileName;
  }
}

if (!function_exists('labelLevel')) {
  function labelLevel($name){

    if($name == 'superadmin'){
      $label = '<span class="badge badge-success">Super</span>';
    } elseif($name == 'admin'){
      $label = '<span class="badge badge-primary">Admin</span>';
    } elseif($name == 'user'){
      $label = '<span class="badge badge-warning">User</span>';
    } else {
      $label = '<span class="badge badge-danger">Tidak Diketahui</span>';
    }

    return $label;
  }
}

if (!function_exists('labelRole')) {
  function labelRole($name) {
    $dataRole = \DB::table('role')->where('name', $name)->first();

    if (!$dataRole) {
      return '<span class="default-class badge badge-danger">Role not found</span>'; 
        }

    $label = '<span class="'.$dataRole->class.'">'.$dataRole->name.'</span>';
    return $label;
  }
}


if (!function_exists('NamaTA')) {
  function NamaTA($id){
    if($id!='-'){
      $dataRole = \DB::table('users')->where('id', $id)->first();
      $class = $dataRole->name;
    } else {
      $class = '-';
    }
    
    return $class;
  }
}

if (!function_exists('NamaUserList')) {
  function NamaUserList($id){
    $data = '';
    if($id != '-'){
      $query = \DB::table('users')->where('id', $id)->first();
      $data = '<li class="list-group-item">'.$query->name.'</li><br>';
    } else {
      $data = '';
    }
    return $data;
  }
}


if (!function_exists('NamaWilayah')) {
  function NamaWilayah($data){
    if($data == 'Jakarta Pusat'){
      $label = '<span class="badge badge-primary">'.$data.'</span>';
    } else if($data == 'Jakarta Utara'){
      $label = '<span class="badge badge-info">'.$data.'</span>';
    } else if($data == 'Jakarta Selatan'){
      $label = '<span class="badge badge-success">'.$data.'</span>';
    } else if($data == 'Jakarta Barat'){
      $label = '<span class="badge badge-danger">'.$data.'</span>';
    } else if($data == 'Jakarta Timur'){
      $label = '<span class="badge badge-warning">'.$data.'</span>';
    } else if($data == 'Kepulauan Seribu'){
      $label = '<span class="badge badge-info">'.$data.'</span>';
    } else if($data == 'Dinas'){
      $label = '<span class="badge badge-dark">'.$data.'</span>';
    } else {
      $label = '-';
    }

    return $label;
  }
}

	