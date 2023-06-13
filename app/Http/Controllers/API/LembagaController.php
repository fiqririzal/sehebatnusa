<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Lembaga;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LembagaController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'nama' => 'required',
            'logo' => 'required|mimes:jpeg,png,gif,jpg',
        ];
        $message = [
            'nama.required' => 'mohon isikan nama nya bro',
            'logo.required'   => 'mohon isikan gambar nya bro',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }
        try {
            $extension = $request->file('logo')->getClientOriginalExtension();
            $image = strtotime(date('Y-m-d H:i:s')) . '.' . $extension;
            $destination = public_path('images/lembaga');
            $request->file('logo')->move($destination, $image);

            $lembaga = Lembaga::create([
                'nama' => $request->judul,
                'logo' => $image,
            ]);
            $lembaga->logo = asset('/images/lembaga/' . $lembaga->logo);
            return apiResponse(201, 'success', 'Lembaga berhasil ditambah', $lembaga);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
    public function destroy($id){
        try {
            $fileName = Lembaga::where('id', $id)->first()->logo;
            $pleaseRemove = base_path('public/images/lembaga/').$fileName;
            if(file_exists($pleaseRemove)) {
                unlink($pleaseRemove);
            }
            Lembaga::where('id', $id)->delete();
            return apiResponse(202, 'success', 'Lembaga berhasil dihapus');
        } catch (Exception $e) {
            return apiResponse(400, 'gagal', 'error', $e);
        }
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'nama' => 'required',
            'logo' => 'required|mimes:jpeg,png,gif,jpg',
        ];
        $message = [
            'nama.required' => 'mohon isikan judul nya bro',
            'logo.required'   => 'mohon isikan gambar nya bro',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }
        try{
            $fileName = Lembaga::where('id', $id)->first()->logo;

            if($fileName)
            {
                $pleaseRemove = base_path('public/images/lembaga/').$fileName;

                if(file_exists($pleaseRemove)) {
                    unlink($pleaseRemove);
                }
            }

            $extension = $request->file('logo')->getClientOriginalExtension();
            $image = strtotime(date('Y-m-d H:i:s')).'.'.$extension;
            $destination = base_path('public/images/lembaga');
            $request->file('logo')->move($destination, $image);

            Lembaga::where('id', $id)->update([
                'nama' => $request->nama,
                'logo' => $image,
            ]);

            $lembaga = Lembaga::where('id', $id)->first();
            $lembaga->gambar = asset('/images/lembaga/' .$lembaga->logo);

            return apiResponse(202, 'success', 'Lembaga berhasil disunting', $lembaga);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
    public function index()
    {
        $data = Lembaga::get();
        foreach ($data as $datas) {
            $datas->logo = asset('/images/lembaga/' . $datas->logo);
        }
        return apiResponse(200, 'success', 'semua data Lembaga', $data);
    }
    public function show($id)
    {
        $data = Lembaga::where('id', $id)->first();
        $data->logo = asset('/images/lembaga/' .$data->logo);
        return apiResponse(200, 'success', 'Lembaga show data', $data);
    }
}
