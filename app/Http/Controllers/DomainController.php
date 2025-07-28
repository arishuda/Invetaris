<?php

namespace App\Http\Controllers;
use App\Domain;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
class DomainController extends Controller
{
    public function index(Request $request)
    {
        $domains = \DB::table('domain')->orderBy('created_at', 'desc')->get();
        $title = 'E-Aset | Domain';
        return view('domain.index', compact('domains','title'));
    }
     

    public function store(Request $request)
    {
        $Domain = Domain::create([
            'nama' => $request->nama,
            'expired_date' => $request->expired_date,
            'registrar' => $request->registrar,
            'email' => $request->email,
            'password' => $request->password,
            'company' => $request->company,
            'remark' => $request->remark,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $Domain->save();
        Alert::success('Success', 'Domain created successfully.');
        // dd($Domain);
        return redirect()->route('domain');
    }

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'expired_date' => 'required|date',
            'registrar' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'company' => 'required',
            'remark' => 'nullable|string',
        ]);
        $domain = Domain::findOrFail($id);
        $domain->update($request->all());
        Alert::success('Success', 'Domain updated successfully.');
         return redirect()->route('domain');   
    }

    public function destroy($id)
    {
        $domain = Domain::findOrFail($id);
        $domain->delete();
        Alert::success('Danger', 'Domain deleted successfully.');
        return redirect()->route('domain');
    }

}
