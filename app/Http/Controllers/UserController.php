<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Redirect;
use Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Vinkla\Hashids\Facades\Hashids;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
    //  * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (Auth::user()->level == 'user') {
            // Alert::warning('Anda dilarang masuk ke area ini');
            return redirect()->to(url('/home'));
        } elseif ((Auth::user()->level == 'admin')) {
            // Alert::warning('Anda dilarang masuk ke area ini');
            return redirect()->to(url('/home'));
        }
        $title = 'E-Aset | User';
        $datas = User::where('level', 'user')->orderBy('created_at', 'DESC')->get();
        return view('auth.user', compact('datas', 'title'));
    }

    public function admin()
    {
        if ((Auth::user()->level == 'user')) {
            // Alert::warning('Anda dilarang masuk ke area ini');
            return redirect()->to(url('/home'));
        } elseif ((Auth::user()->level == 'admin')) {
            // Alert::warning('Anda dilarang masuk ke area ini');
            return redirect()->to(url('/home'));
        }
        $title = 'E-Aset | Admin';
        $datas = User::where('level', "admin")->where('active', 1)->get();
        return view('auth.admin', compact('datas','title'));
    }

    public function create()
    {
        if ((Auth::user()->level == 'user')) {
            // Alert::warning('Anda dilarang masuk ke area ini');
            return redirect()->to(url('/home'));
        }

        $dataRole = Role::all();
        $title = 'E-Aset | Tambah User';
        return view('auth.register', compact('dataRole','title'));
    }

    public function store(Request $request)
    {
        $checkData = User::where('username', $request->input('username'))->orWhere('email', $request->input('email'))->first();

        if (!empty($checkData)) {
            Alert::warning('User Dengan Email atau Username tersebut telah digunakan');
            return redirect()->back();
        }

        $this->validate($request, [
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);


        if ($request->file('gambar')) {
            $gambar = uploadFile($request->file('gambar'), "images/user");
        } else {
            $gambar = "no_user.png";
        }

        User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'level' => $request->input('level'),
            'jabatan' => $request->input('jabatan'),
            'password' => bcrypt(($request->input('password'))),
            'gambar' => $gambar,
            'active' => true,
            'role' => $request->input('role')
        ]);

        if ($request->input('level') == "admin") {
            Alert::success('Admin Berhasil ditambahkan !');
            return redirect()->to(url('/admin'));
        } else {
            Alert::success('User Berhasil ditambahkan !');
            return redirect()->to(url('/user'));
        }
    }


    // public function ubahrole($id)
    // {
    //     $data = User::findOrFail($id);

    //     return view('auth.gantirole', compact('data'));
    // }

    // public function perbarui(Request $request, $id)
    // {
    //     User::find($id)->update($request->all());

    //     Alert::success('Berhasil', 'Data profil berhasil diperbarui.');
    //     return redirect()->to('user');
    // }

    // public function show($id) {
    //     if((Auth::user()->level == 'user') && (Auth::user()->id != $id)) {
    //             Alert::info('Oopss..', 'Anda dilarang masuk ke area ini.');
    //             return redirect()->to('/');
    //     }

    //     $data = User::findOrFail($id);
    //     $ckodekot = Kotajkt::where('kodekota',$data->penempatan)->pluck('namakota');

    //     $ckodewil = Kodewil::where('kode_lokasi',$data->penempatan)->pluck('lokasi');

    //     return view('auth.show', compact('data','ckodekot','ckodewil'));
    // }

    public function edit($id)
    {

        $decodedIdArray = Hashids::decode($id);
        if (empty($decodedIdArray)) {
            abort(404, 'Invalid ID'); 
        }
        $ids = $decodedIdArray[0];

        $data = User::findOrFail($ids);

        if ((Auth::user()->level == 'user')) {
            // Alert::warning('Anda dilarang masuk ke area ini');
            return redirect()->to(url('/home'));
        }

        $dataRole = Role::all();
        $title = 'E-Aset | Edit User';

        return view('auth.edit', compact('data', 'dataRole','title'));
    }

    public function update(Request $request, $id)
    {
        $decodedIdArray = Hashids::decode($id);
        if (empty($decodedIdArray)) {
            abort(404, 'Invalid ID'); 
        }
        $ids = $decodedIdArray[0];

        $user_data = User::findOrFail($ids);

        if ($request->file('gambar')) {
            $user_data->gambar = uploadFile($request->file('gambar'), "images/user");
        }

        $user_data->name = $request->input('name');
        $user_data->email = $request->input('email');
        $user_data->level = $request->input('level');
        $user_data->jabatan = $request->input('jabatan');
        $user_data->role = $request->input('role');

        if ($request->has('active')) {
            $user_data->active = true;
        } else {
            $user_data->active = false;
        }

        if ($request->input('password')) {
            $user_data->password = bcrypt(($request->input('password')));
        }

        $user_data->update();

        if ($user_data->level == 'admin') {
            Alert::success('Data profil berhasil diperbarui');
            return redirect()->to(url('/admin'));
        } else {
            Alert::success('Data profil berhasil diperbarui');
            return redirect()->to(url('/user'));
        }
    }

    public function destroy($id)
    {
        $user_data = User::findOrFail($id);
        $user_data->delete();

        if ($user_data->level == 'admin') {
            Alert::success('Admin berhasil Didelete');
            return redirect()->to(url('/admin'));
        } else {
            Alert::success('User berhasil Didelete');
            return redirect()->to(url('/user'));
        }
    }


}