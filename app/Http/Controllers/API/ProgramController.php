<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'program' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|mimes:jpeg,png,gif,jpg',
        ];
        $message = [
            'program.required' => 'mohon isikan judul nya bro',
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
            $destination = public_path('images/program');
            $request->file('gambar')->move($destination, $image);

            $program = Program::create([
                'program' => $request->program,
                'deskripsi' => $request->deskripsi,
                'gambar' => $image,
            ]);
            $program->gambar = asset('/images/program/' . $program->gambar);
            return apiResponse(201, 'success', 'program berhasil ditambah', $program);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
    public function destroy($id){
        try {
            $fileName = Program::where('id', $id)->first()->gambar;
            $pleaseRemove = base_path('public/images/program/').$fileName;
            if(file_exists($pleaseRemove)) {
                unlink($pleaseRemove);
            }
            Program::where('id', $id)->delete();
            return apiResponse(202, 'success', 'Program berhasil dihapus');
        } catch (Exception $e) {
            return apiResponse(400, 'gagal', 'error', $e);
        }
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'program' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|mimes:jpeg,png,gif,jpg',
        ];
        $message = [
            'program.required' => 'mohon isikan judul nya bro',
            // 'slug.required'    => 'mohon isikan nama nya bro',
            'deskripsi.required'  => 'mohon isikan detail nya bro',
            'gambar.required'   => 'mohon isikan gambar nya bro',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }
        try{
            $fileName = Program::where('id', $id)->first()->gambar;

            if($fileName)
            {
                $pleaseRemove = base_path('public/images/program/').$fileName;

                if(file_exists($pleaseRemove)) {
                    unlink($pleaseRemove);
                }
            }

            $extension = $request->file('gambar')->getClientOriginalExtension();
            $image = strtotime(date('Y-m-d H:i:s')).'.'.$extension;
            $destination = base_path('public/images/program');
            $request->file('gambar')->move($destination, $image);

            Program::where('id', $id)->update([
                'program' => $request->program,
                'deskripsi' => $request->deskripsi,
                'gambar' => $image,
            ]);

            $program = Program::where('id', $id)->first();
            $program->gambar = asset('/images/program/' .$program->gambar);

            return apiResponse(202, 'success', 'program berhasil disunting', $program);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
    public function index()
    {
        $data = Program::get();
        foreach ($data as $datas) {
            $datas->file = asset('/images/program/' . $datas->file);
        }
        return apiResponse(200, 'success', 'semua data Program', $data);
    }
    public function show($id)
    {
        $data = Program::where('id', $id)->first();
        $data->gambar = asset('/images/program/' .$data->gambar);
        return apiResponse(200, 'success', 'Program show data', $data);
    }
}
