@extends('layouts.lteapp')
@section('title-page')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>TAMBAH USER</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                <li class="breadcrumb-item active">Tambah User</li>
            </ol>
        </div>
    </div>
@endsection
@section('content')
<body>
    <form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data" autocomplete="off">
        {{ csrf_field() }}
        <div class="row">
            <div class="col">
                {{-- <div class="row flex-grow"> --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <h4 class="text-white"> </h4>
                            </div>
                            {{-- <h4 class="card-title">Tambah user baru</h4> --}}
                            <div class="card-body">
                                
                                <div class="row">
                                    <div class="col-md-9 col-sm-12">
                                        <div class="form-group">
                                            <label for="gambar" class="control-label">Gambar :</label>
                                            <img class="product" style="width:200px; height:auto;" src="{{ asset('images/user/no_user.png') }}"/>
                                            <input type="file" class="uploads form-control" style="margin-top: 20px;" name="gambar">
                                        </div>
                                    </div>

                                    <div class="col-md-9 col-sm-12">

                                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                            <label for="name" class="control-label">Name :</label>
                                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                                    <label for="username" class="control-label">Username :</label>
                                                    <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                                                    @if ($errors->has('username'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('username') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                                    <label for="email" class="control-label">E-Mail Address :</label>
                                                    <input autocomplete="off" id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                                    @if ($errors->has('email'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group{{ $errors->has('jabatan') ? ' has-error' : '' }}">
                                                    <label for="jabatan" class="control-label">Jabatan</label>
                                                    <input id="jabatan" type="text" class="form-control" name="jabatan" value="{{ old('jabatan') }}" required>
                                                    @if ($errors->has('jabatan'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('jabatan') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group{{ $errors->has('level') ? ' has-error' : '' }}">
                                                    <label for="level" class="control-label">Level :</label>
                                                    <select class="form-control" name="level" required="">
                                                        <option value="">-- Pilih Level --</option>
                                                        <option value="admin">Admin</option>
                                                        <option value="user">User</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
                                                    <label for="level" class="control-label">Role :</label>
                                                    <select class="form-control" name="role" required="">
                                                        <option value="">-- Pilih Role --</option>
                                                        @foreach($dataRole as $row)
                                                        <option value="{{ $row->name }}">{{ ucfirst($row->name) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                                    <label for="password" class="control-label">Password :</label>
                                                    <input id="password" type="password" class="form-control" onkeyup='check();' name="password" required>
                                                    @if ($errors->has('password'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password-confirm" class="control-label">Konfirmasi Password :</label>
                                                    <input id="confirm_password" type="password" onkeyup="check()" class="form-control" name="password_confirmation" required>
                                                    <span id='message'></span>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary pull-right" id="submit"> Register </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            {{-- </div> --}}

        </div>
    </form>
</body>
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
            if (document.getElementById('password').value == document.getElementById('confirm_password').value) {
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
