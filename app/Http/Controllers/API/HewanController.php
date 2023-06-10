<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Hewan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HewanController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'judul' => 'required',
            'detail' => 'required',
            'harga' => 'required',
            'image' => 'required|mimes:jpeg,png,gif,jpg',
        ];
        $message = [
            'judul.required' => 'mohon isikan judul nya bro',
            // 'slug.required'    => 'mohon isikan nama nya bro',
            'detail.required'  => 'mohon isikan detail nya bro',
            'harga.required'   => 'mohon isikan harga nya bro',
            'image.required'   => 'mohon isikan gambar nya bro',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }
        try {
            $extension = $request->file('image')->getClientOriginalExtension();
            $image = strtotime(date('Y-m-d H:i:s')) . '.' . $extension;
            $destination = public_path('images/hewan');
            $request->file('image')->move($destination, $image);

            $hewan = Hewan::create([
                'judul' => $request->judul,
                'slug' => Str::slug($request->judul),
                'detail' => $request->detail,
                'harga' => $request->harga,
                'image' => $image,
            ]);
            $hewan->image = asset('/images/hewan/' . $hewan->image);
            return apiResponse(201, 'success', 'Hewan berhasil ditambah', $hewan);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
    public function index()
    {
        $data = Hewan::get();
        foreach ($data as $datas) {
            $datas->image = asset('/images/hewan/' . $datas->image);
        }
        return apiResponse(200, 'success', 'semua data Hewan', $data);
    }
    public function show($id)
    {
        $data = Hewan::where('id', $id)->first();
        $data->image = asset('/images/hewan/' .$data->image);
        return apiResponse(200, 'success', 'Hewan show data', $data);
    }
    public function destroy($id){
        try {
            $fileName = Hewan::where('id', $id)->first()->image;
            $pleaseRemove = base_path('public/images/hewan/').$fileName;
            if(file_exists($pleaseRemove)) {
                unlink($pleaseRemove);
            }
            Hewan::where('id', $id)->delete();
            return apiResponse(202, 'success', 'Hewan berhasil dihapus');
        } catch (Exception $e) {
            return apiResponse(400, 'gagal', 'error', $e);
        }
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'judul' => 'required',
            'detail' => 'required',
            'harga' => 'required',
            'image' => 'required|mimes:jpeg,png,gif,jpg',
        ];
        $message = [
            'judul.required' => 'mohon isikan judul nya bro',
            // 'slug.required'    => 'mohon isikan nama nya bro',
            'detail.required'  => 'mohon isikan detail nya bro',
            'harga.required'   => 'mohon isikan harga nya bro',
            'image.required'   => 'mohon isikan gambar nya bro',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }
        try{
            $fileName = Hewan::where('id', $id)->first()->image;

            if($fileName)
            {
                $pleaseRemove = base_path('public/images/hewan/').$fileName;

                if(file_exists($pleaseRemove)) {
                    unlink($pleaseRemove);
                }
            }

            $extension = $request->file('image')->getClientOriginalExtension();
            $image = strtotime(date('Y-m-d H:i:s')).'.'.$extension;
            $destination = base_path('public/images/produk');
            $request->file('image')->move($destination, $image);

            Hewan::where('id', $id)->update([
                'judul' => $request->judul,
                'slug' => Str::slug($request->judul),
                'detail' => $request->detail,
                'harga' => $request->harga,
                'image' => $image,
            ]);

            $hewan = Hewan::where('id', $id)->first();
            $hewan->image = asset('/images/hewan/' .$hewan->image);

            return apiResponse(202, 'success', 'hewan berhasil disunting', $hewan);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
}
