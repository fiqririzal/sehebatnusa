<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Testimoni;
use Illuminate\Support\Facades\Validator;

class TestimoniController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'judul' => 'required',
            'gambar' => 'required|mimes:jpeg,png,gif,jpg',
        ];
        $message = [
            'judul.required' => 'mohon isikan judul nya bro',
            'gambar.required'   => 'mohon isikan gambar nya bro',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }
        try {
            $extension = $request->file('gambar')->getClientOriginalExtension();
            $image = strtotime(date('Y-m-d H:i:s')) . '.' . $extension;
            $destination = public_path('images/testimoni');
            $request->file('gambar')->move($destination, $image);

            $testimoni = Testimoni::create([
                'judul' => $request->judul,
                'gambar' => $image,
            ]);
            $testimoni->gambar = asset('/images/testimoni/' . $testimoni->gambar);
            return apiResponse(201, 'success', 'testimoni berhasil ditambah', $testimoni);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
    public function destroy($id){
        try {
            $fileName = Testimoni::where('id', $id)->first()->gambar;
            $pleaseRemove = base_path('public/images/testimoni/').$fileName;
            if(file_exists($pleaseRemove)) {
                unlink($pleaseRemove);
            }
            Testimoni::where('id', $id)->delete();
            return apiResponse(202, 'success', 'Testimoni berhasil dihapus');
        } catch (Exception $e) {
            return apiResponse(400, 'gagal', 'error', $e);
        }
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'judul' => 'required',
            'gambar' => 'required|mimes:jpeg,png,gif,jpg',
        ];
        $message = [
            'judul.required' => 'mohon isikan judul nya bro',
            'gambar.required'   => 'mohon isikan gambar nya bro',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }
        try{
            $fileName = Testimoni::where('id', $id)->first()->gambar;

            if($fileName)
            {
                $pleaseRemove = base_path('public/images/testimoni/').$fileName;

                if(file_exists($pleaseRemove)) {
                    unlink($pleaseRemove);
                }
            }

            $extension = $request->file('gambar')->getClientOriginalExtension();
            $image = strtotime(date('Y-m-d H:i:s')).'.'.$extension;
            $destination = base_path('public/images/testimoni');
            $request->file('gambar')->move($destination, $image);

            Testimoni::where('id', $id)->update([
                'judul' => $request->judul,
                'gambar' => $image,
            ]);

            $testimoni = Testimoni::where('id', $id)->first();
            $testimoni->gambar = asset('/images/tes$testimoni/' .$testimoni->gambar);

            return apiResponse(202, 'success', 'program berhasil disunting', $testimoni);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
    public function index()
    {
        $data = Testimoni::get();
        foreach ($data as $datas) {
            $datas->gambar = asset('/images/testimoni/' . $datas->gambar);
        }
        return apiResponse(200, 'success', 'semua data Program', $data);
    }
    public function show($id)
    {
        $data = Testimoni::where('id', $id)->first();
        $data->gambar = asset('/images/testimoni/' .$data->gambar);
        return apiResponse(200, 'success', 'Program show data', $data);
    }
}
