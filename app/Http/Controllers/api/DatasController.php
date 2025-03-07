<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\WargaModel;

class DatasController extends Controller
{
    public function insWarga(Request $request)
    {
        // dd($request->all());
        $insWarga = WargaModel::create([
            'id_warga' => str::uuid(),
            'nama' => $request->nama,
            'blok' => $request->blok,
            'jenis_kelamin' => $request->jk,
            'status_tinggal' => $request->status_tinggal,
            'no_telp' => $request->no_telp,
        ]);

        if ($insWarga) {
            return
            [
                'success' => true,
                'message' => 'Data berhasil disimpan',
            ];
        } else {
            return
            [
                'success' => false,
                'message' => 'Data gagal disimpan',
            ];
        }
    }

    public function listWarga(Request $request)
    {
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;

        $datas = WargaModel::where(function ($q) use ($search) {
                $q
                    ->where('nama', 'like', '%' . $search . '%')
                    ->orwhere('blok', 'like', '%' . $search . '%')
                    ->orwhere('status_tinggal', 'like', '%' . $search . '%');
            })
            ->orderBy('blok', 'asc')
            ->skip($start)
            ->take($length)
            ->get();

        $count = WargaModel::where(function ($q) use ($search) {
            $q
                ->where('nama', 'like', '%' . $search . '%')
                ->orwhere('blok', 'like', '%' . $search . '%')
                ->orwhere('status_tinggal', 'like', '%' . $search . '%');
        })
            ->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $datas,
        ];
    }
}
