<?php

namespace App\Http\Controllers;
use App\Regions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegionsController extends Controller
{
    public function index()
    {
        if ((\Auth::user()->level == 'user')) {
            //   \Alert::warning('Anda dilarang masuk ke area ini');
            return redirect()->to(url('/home'));
        }
        // $regions = Regions::all();
        $regions = Regions::orderBy('created_at', 'desc')->get();
        $title = 'E-Aset | Regions ';
        foreach ($regions as $region) {
            $region->details = json_decode($region->detail, true);
        }

        return view('regions.index', compact('regions', 'title'), );
    }

    public function post(Request $request)
    {
        //dd($request->all());
        $uuid = Str::uuid()->toString();
        $regionId = \DB::table('regions')->insertGetId([
            'id' => $uuid,
            'level' => $request->level,
            'code' => $request->code,
            'city' => $request->city,
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'detail' => json_encode([
                'address' => $request['address'],
                'postal_code' => $request['postal_code'],
                'telphone' => $request['telphone'],
                'email' => $request['email'],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \Alert::success('Data Berhasil Di Input');
        return redirect('/regions');
    }

    public function update(Request $request, $id){
        $region = Regions::find($id);
        $region->update([
            $region->level => $request->level,
            $region->code => $request->code,
            $region->city => $request->city,
            $region->name => $request->name,
            $region->latitude => $request->latitude,
            $region->longitude => $request->longitude,
            $region->detail => json_encode([
                $region->address => $request['address'],
                $region->postal_code => $request['postal_code'],
                $region->telphone => $request['telphone'],
                $region->email => $request['email'],
            ]),
            $region->updated_at => now(),
            $region->save()
        ]);
        \Alert::success('Data Berhasil Di Update');
        return view('regions.edit', compact('region', 'title'));
    }


    public function getRegionsAjax(Request $request)
    {
        $cities = Regions::select('city')->distinct()->orderBy('city')->get();
        $regions = Regions::select('code', 'name', 'level', 'city', 'latitude', 'longitude', 'created_at');
        if ($request->has('city')) {
            $regions->where('city', $request->input('city'));
        }
        $regions = $regions->get();
        $regions->each(function($region) use ($request) {
            $region->detail = json_encode([
                'address' => $request->input('address'),
                'postal_code' => $request->input('postal_code'),
                'telphone' => $request->input('telphone'),
                'email' => $request->input('email'),
            ]);
        });
        return response()->json([
            'regions' => $regions,
            'cities' => $cities,
        ]);
    }
    public function destroy($id)
    {
        $region = Regions::where('id', $id)->first();
        \Alert::error('Data Berhasil Di Delete');
        $region->delete();
        return redirect('/regions');
    }
}
