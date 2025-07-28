@extends('layouts.lteapp')
@section('title-page')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>EDIT USER</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Edit User</li>
            </ol>
        </div>
    </div>
@endsection
@section('content')
    <form action="{{ route('user.update', \Hashids::encode($data->id)) }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('put') }}
        <div class="row">
            <div class="col-md-12 d-flex align-items-stretch grid-margin">
                {{-- <div class="row flex-grow"> --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <h4 class="text-white"> </h4>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <div class="">
                                                <img class="product" style="width:200px; height:auto;" @if ($data->gambar) src="{{ asset('images/user/' . $data->gambar) }}" @else src="{{ asset('images/user/no_user.png') }}" @endif />
                                                <input type="file" class="uploads form-control" style="margin-top: 20px;" name="gambar">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-9 col-sm-12">

                                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                            <label for="name" class="control-label">Name :</label>
                                            <div class="">
                                                <input id="name" type="text" class="form-control" name="name" value="{{ $data->name }}" required autofocus>
                                                @if ($errors->has('name'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                                    <label for="username" class="control-label">Username :</label>
                                                    <div class="">
                                                        <input id="username" type="text" class="form-control" name="username"
                                                            value="{{ $data->username }}" required>
                                                        @if ($errors->has('username'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('username') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                                    <label for="email" class="control-label">E-Mail Address :</label>
                                                    <div class="">
                                                        <input id="email" type="email" class="form-control" name="email"
                                                            value="{{ $data->email }}" required>
                                                        @if ($errors->has('email'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('email') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group{{ $errors->has('jabatan') ? ' has-error' : '' }}">
                                                    <label for="jabatan" class="control-label">Jabatan</label>
                                                    <div class="">
                                                        <input id="jabatan" type="jabatan" class="form-control" name="jabatan"
                                                            value="{{ $data->jabatan }}" required>
                                                        @if ($errors->has('jabatan'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('jabatan') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group{{ $errors->has('level') ? ' has-error' : '' }}">
                                                    <label for="level" class="control-label">Role Level</label>
                                                    <div class="">
                                                        <select name="level" class="form-control" id="level" required>
                                                            <option value="">-- Pilih Role Level --</option>
                                                            <option value="superadmin" @if ($data->level == 'superadmin') selected @endif>Superadmin</option>
                                                            <option value="admin" @if ($data->level == 'admin') selected @endif>Admin</option>
                                                            <option value="user" @if ($data->level == 'user') selected @endif>User</option>
                                                        </select>
                                                        
                                                        @if ($errors->has('level'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('level') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                                                    <label for="level" class="control-label">Role :</label>
                                                    <select class="form-control" name="role" required="">
                                                        <option value="">-- Pilih Role --</option>
                                                        @foreach($dataRole as $row)
                                                        <option value="{{ $row->name }}" @if($data->role == $row->name) selected @endif>{{ ucfirst($row->name) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                                    <label for="password" class="control-label">Password :</label>
                                                    <div class="">
                                                        <input id="password" type="password" class="form-control" onkeyup='check();'
                                                            name="password">
                                                        @if ($errors->has('password'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('password') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password-confirm" class="control-label">Konfirmasi Password
                                                        :</label>
                                                    <div class="">
                                                        <input id="confirm_password" type="password" onkeyup="check()"
                                                            class="form-control" name="password_confirmation">
                                                        <span id='message'></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <div class="form-group"> --}}
                                        <div class="custom-control custom-switch" style="margin-left:10px; margin-bottom:20px;">
                    
                                               @if($data->active==1)
                                            <input type="checkbox" class="custom-control-input" name="active" id="active" @if($data->active == 1) checked @else @endif>
                                            <label class="custom-control-label" for="active">Status Active</label>
                                             @else
                                              <input type="checkbox" class="custom-control-input" name="active" id="active"   >
                                              <label class="custom-control-label" for="active">Status InActive</label>
                                               @endif
                                        </div>

                                            {{-- <input type="checkbox" @if($data->active) checked @else @endif name="active" class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span> --}}
                                        {{-- </div> --}}

                                        <button type="submit" class="btn btn-primary" id="submit">
                                            Update
                                        </button>&nbsp;

                                        @if ($data->level == 'admin')
                                            <a href="{{ url('/admin') }}" class="btn btn-secondary">Back</a>
                                        @else 
                                            <a href="{{ route('user.index') }}" class="btn btn-secondary">Back</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- </div> --}}
            </div>

        </div>
    </form>
@endsection


@section('js')
    <script type="text/javascript">
        function readURL() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(input).prev().attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(function() {
            $(".uploads").change(readURL)
            $("#f").submit(function() {
                return false
            })
        })


        var check = function() {
            if (document.getElementById('password').value ==
                document.getElementById('confirm_password').value) {
                document.getElementById('submit').disabled = false;
                document.getElementById('message').style.color = 'green';
                document.getElementById('message').innerHTML = 'matching';
            } else {
                document.getElementById('submit').disabled = true;
                document.getElementById('message').style.color = 'red';
                document.getElementById('message').innerHTML = 'not matching';
            }
        }
    </script>
@endsection
