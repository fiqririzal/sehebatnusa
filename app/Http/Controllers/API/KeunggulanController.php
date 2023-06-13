<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Keunggulan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class KeunggulanController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'judul' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|mimes:jpeg,png,gif,jpg',
        ];
        $message = [
            'judul.required' => 'mohon isikan judul nya bro',
            // 'slug.required'    => 'mohon isikan nama nya bro',
            'deskripsi.required'  => 'mohon isikan deskripsi nya bro',
            'gambar.required'   => 'mohon isikan gambar nya bro',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }
        try {
            $extension = $request->file('gambar')->getClientOriginalExtension();
            $image = strtotime(date('Y-m-d H:i:s')) . '.' . $extension;
            $destination = public_path('images/keunggulan');
            $request->file('gambar')->move($destination, $image);

            $keunggulan = Keunggulan::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'gambar' => $image,
            ]);
            $keunggulan->gambar = asset('/images/keunggulan/' . $keunggulan->gambar);
            return apiResponse(201, 'success', 'Keunggulan berhasil ditambah', $keunggulan);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
    public function destroy($id){
        try {
            $fileName = Keunggulan::where('id', $id)->first()->file;
            $pleaseRemove = base_path('public/images/keunggulan/').$fileName;
            if(file_exists($pleaseRemove)) {
                unlink($pleaseRemove);
            }
            Keunggulan::where('id', $id)->delete();
            return apiResponse(202, 'success', 'Keunggulan berhasil dihapus');
        } catch (Exception $e) {
            return apiResponse(400, 'gagal', 'error', $e);
        }
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'judul' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|mimes:jpeg,png,gif,jpg',
        ];
        $message = [
            'judul.required' => 'mohon isikan judul nya bro',
            // 'slug.required'    => 'mohon isikan nama nya bro',
            'deskripsi.required'  => 'mohon isikan detail nya bro',
            'gambar.required'   => 'mohon isikan gambar nya bro',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }
        try{
            $fileName = Keunggulan::where('id', $id)->first()->image;

            if($fileName)
            {
                $pleaseRemove = base_path('public/images/keunggulan/').$fileName;

                if(file_exists($pleaseRemove)) {
                    unlink($pleaseRemove);
                }
            }

            $extension = $request->file('gambar')->getClientOriginalExtension();
            $image = strtotime(date('Y-m-d H:i:s')).'.'.$extension;
            $destination = base_path('public/images/keunggulan');
            $request->file('gambar')->move($destination, $image);

            Keunggulan::where('id', $id)->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'gambar' => $image,
            ]);

            $keunggulan = Keunggulan::where('id', $id)->first();
            $keunggulan->gambar = asset('/images/keunggulan/' .$keunggulan->gambar);

            return apiResponse(202, 'success', 'keunggulan berhasil disunting', $keunggulan);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
    public function index()
    {
        $data = Keunggulan::get();
        foreach ($data as $datas) {
            $datas->gambar = asset('/images/Keunggulan/' . $datas->gambar);
        }
        return apiResponse(200, 'success', 'semua data Artikel', $data);
    }
    public function show($id)
    {
        $data = Keunggulan::where('id', $id)->first();
        $data->gambar = asset('/images/keunggulan/' .$data->gambar);
        return apiResponse(200, 'success', 'Artikel show data', $data);
    }
}
