<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Artikel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ArtikelController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'judul' => 'required',
            'deskripsi' => 'required',
            'file' => 'required|mimes:jpeg,png,gif,jpg',
        ];
        $message = [
            'judul.required' => 'mohon isikan judul nya bro',
            // 'slug.required'    => 'mohon isikan nama nya bro',
            'deksripsi.required'  => 'mohon isikan deskripsi nya bro',
            'file.required'   => 'mohon isikan gambar nya bro',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }
        try {
            $extension = $request->file('file')->getClientOriginalExtension();
            $image = strtotime(date('Y-m-d H:i:s')) . '.' . $extension;
            $destination = public_path('images/artikel');
            $request->file('file')->move($destination, $image);

            $artikel = Artikel::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'file' => $image,
            ]);
            $artikel->file = asset('/images/artikel/' . $artikel->image);
            return apiResponse(201, 'success', 'artikel berhasil ditambah', $artikel);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
    public function destroy($id){
        try {
            $fileName = Artikel::where('id', $id)->first()->file;
            $pleaseRemove = base_path('public/images/artikel/').$fileName;
            if(file_exists($pleaseRemove)) {
                unlink($pleaseRemove);
            }
            Artikel::where('id', $id)->delete();
            return apiResponse(202, 'success', 'Artikel berhasil dihapus');
        } catch (Exception $e) {
            return apiResponse(400, 'gagal', 'error', $e);
        }
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'judul' => 'required',
            'deskripsi' => 'required',
            'file' => 'required|mimes:jpeg,png,gif,jpg',
        ];
        $message = [
            'judul.required' => 'mohon isikan judul nya bro',
            // 'slug.required'    => 'mohon isikan nama nya bro',
            'deskripsi.required'  => 'mohon isikan detail nya bro',
            'file.required'   => 'mohon isikan gambar nya bro',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }
        try{
            $fileName = Artikel::where('id', $id)->first()->image;

            if($fileName)
            {
                $pleaseRemove = base_path('public/images/artikel/').$fileName;

                if(file_exists($pleaseRemove)) {
                    unlink($pleaseRemove);
                }
            }

            $extension = $request->file('file')->getClientOriginalExtension();
            $image = strtotime(date('Y-m-d H:i:s')).'.'.$extension;
            $destination = base_path('public/images/artikel');
            $request->file('file')->move($destination, $image);

            Artikel::where('id', $id)->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'file' => $image,
            ]);

            $artikel = Artikel::where('id', $id)->first();
            $artikel->image = asset('/images/artikel/' .$artikel->image);

            return apiResponse(202, 'success', 'artikel berhasil disunting', $artikel);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
    public function index()
    {
        $data = Artikel::get();
        foreach ($data as $datas) {
            $datas->file = asset('/images/Artikel/' . $datas->file);
        }
        return apiResponse(200, 'success', 'semua data Artikel', $data);
    }
    public function show($id)
    {
        $data = Artikel::where('id', $id)->first();
        $data->file = asset('/images/artikel/' .$data->file);
        return apiResponse(200, 'success', 'Artikel show data', $data);
    }
}
