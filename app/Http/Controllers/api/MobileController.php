<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\IuranWargaModel;
use App\Models\WargaModel;

class MobileController extends Controller
{

    public function listBlok()
    {
        $blok = DB::table('tb_one')->select('blok')->distinct()->orderBy('blok')->get();

        return response()->json(['data' => $blok]);
    }

    public function listWarga (Request $request){
        $blok = request('blok');
        $warga = WargaModel::select('id_warga', 'nama', 'blok', 'alamat_ktp', 'status_tinggal')
            ->when($blok, function ($query, $blok) {
                return $query->where('blok', $blok);
            })
            ->get();

        return response()->json(['data' => $warga]);
    }

    public function listIuranWarga(Request $request)
    {
        $blok = request('blok');
        $iuranWarga = IuranWargaModel::select('id_iuran', 'blok', 'periode', 'nominal', 'tgl_bayar')
            ->when($blok, function ($query, $blok) {
                return $query->where('blok', $blok);
            })
            ->get();

        return response()->json(['data' => $iuranWarga,
        'success' => true,
        'message' => 'Data iuran warga berhasil diambil']);
    }
}
