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
        $cekDouble = WargaModel::where('blok', $request->blok)->where('status_warga', 'Tinggal')
            ->count();

            if ($cekDouble > 0) {
                return
                [
                    'success' => false,
                    'message' => 'Data sudah ada.',
                ];
            }

        $insWarga = WargaModel::create([
            'id_warga' => str::uuid(),
            'no_kk' => $request->no_kk,
            'no_ktp' => $request->no_ktp,
            'nama' => $request->nama,
            'alamat_ktp' => $request->alamat,
            'blok' => $request->blok,
            'jenis_kelamin' => $request->jk,
            'status_tinggal' => $request->status_tinggal,
            'no_telp' => $request->no_telp,
            'status_warga' => 'Terdaftar',
            'keterangan' => $request->keterangan,
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
        $status_tinggal = $request->status_tinggal;

        $liststatus = [];

        if ($status_tinggal == 'All') {
            $liststatus = ['Stay', 'Kontrak', 'Kos', 'Singgah'];
        } else {
            $liststatus = [$status_tinggal];
        }

        $datas = WargaModel::whereIn('status_tinggal',$liststatus)
        ->where(function ($q) use ($search) {
                $q
                    ->where('nama', 'like', '%' . $search . '%')
                    ->orwhere('no_kk', 'like', '%' . $search . '%')
                    ->orwhere('no_ktp', 'like', '%' . $search . '%')
                    ->orwhere('blok', 'like', '%' . $search . '%')
                    ->orwhere('status_tinggal', 'like', '%' . $search . '%');
            })
            ->orderBy('blok', 'asc')
            ->skip($start)
            ->take($length)
            ->get();

        $count = WargaModel::whereIn('status_tinggal',$liststatus)
        ->where(function ($q) use ($search) {
            $q
                ->where('nama', 'like', '%' . $search . '%')
                ->orwhere('no_kk', 'like', '%' . $search . '%')
                ->orwhere('no_ktp', 'like', '%' . $search . '%')
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

    public function edtWarga (Request $request)
    {
        $edtWarga = WargaModel::where('id_warga', $request->e_id_warga)
            ->update([
                'no_kk' => $request->e_no_kk,
                'no_ktp' => $request->e_no_ktp,
                'nama' => $request->e_nama,
                'alamat_ktp' => $request->e_alamat,
                'blok' => $request->e_blok,
                'jenis_kelamin' => $request->e_jk,
                'status_tinggal' => $request->e_status_tinggal,
                'no_telp' => $request->e_no_telp,
                'keterangan' => $request->e_keterangan,
            ]);

        if ($edtWarga) {
            return
            [
                'success' => true,
                'message' => 'Data berhasil diubah',
            ];
        } else {
            return
            [
                'success' => false,
                'message' => 'Data gagal diubah',
            ];
        }
    }
}
