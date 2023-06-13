<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Target;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TargetController extends Controller
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
            'deksripsi.required'  => 'mohon isikan deskripsi nya bro',
            'gambar.required'   => 'mohon isikan gambar nya bro',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }
        try {
            $extension = $request->file('gambar')->getClientOriginalExtension();
            $image = strtotime(date('Y-m-d H:i:s')) . '.' . $extension;
            $destination = public_path('images/target');
            $request->file('gambar')->move($destination, $image);

            $target = Target::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'gambar' => $image,
            ]);
            $target->gambar = asset('/images/target/' . $target->gambar);
            return apiResponse(201, 'success', 'target berhasil ditambah', $target);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
    public function destroy($id){
        try {
            $fileName = Target::where('id', $id)->first()->gambar;
            $pleaseRemove = base_path('public/images/target/').$fileName;
            if(file_exists($pleaseRemove)) {
                unlink($pleaseRemove);
            }
            Target::where('id', $id)->delete();
            return apiResponse(202, 'success', 'Target berhasil dihapus');
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
            $fileName = Target::where('id', $id)->first()->gambar;

            if($fileName)
            {
                $pleaseRemove = base_path('public/images/target/').$fileName;

                if(file_exists($pleaseRemove)) {
                    unlink($pleaseRemove);
                }
            }

            $extension = $request->file('gambar')->getClientOriginalExtension();
            $image = strtotime(date('Y-m-d H:i:s')).'.'.$extension;
            $destination = base_path('public/images/target');
            $request->file('gambar')->move($destination, $image);

            Target::where('id', $id)->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'gambar' => $image,
            ]);

            $target = Target::where('id', $id)->first();
            $target->gambar = asset('/images/target/' .$target->gambar);

            return apiResponse(202, 'success', 'program berhasil disunting', $target);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
    public function index()
    {
        $data = Target::get();
        foreach ($data as $datas) {
            $datas->gambar = asset('/images/target/' . $datas->gambar);
        }
        return apiResponse(200, 'success', 'semua data Program', $data);
    }
    public function show($id)
    {
        $data = Target::where('id', $id)->first();
        $data->gambar = asset('/images/target/' .$data->gambar);
        return apiResponse(200, 'success', 'Program show data', $data);
    }
}
