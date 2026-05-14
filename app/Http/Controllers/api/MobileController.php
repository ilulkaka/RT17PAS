<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\IuranWargaModel;
use App\Models\WargaModel;
use App\Models\BeginningModel;
use App\Models\LpjModel;

class MobileController extends Controller
{

    public function listPengurus()
    {
        $pengurus = DB::table('tb_pengurus')->where('status', 'Aktif')->get();

        return response()->json(['data' => $pengurus]);
    }

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

    public function saldoBulanIni()
    {
        $start = date('Y-m')."-01";
        $end = date('Y-m-t');

        $beginning = BeginningModel::where('periode', $start)
    ->value('nominal') ?? 0;

    $data = LpjModel::whereBetween('tgl_transaksi', [$start, $end])
    ->selectRaw("
        SUM(CASE WHEN jenis = 'masuk' THEN nominal ELSE 0 END) as total_masuk,
        SUM(CASE WHEN jenis = 'keluar' THEN nominal ELSE 0 END) as total_keluar
    ")
    ->first();

    $saldo = $beginning + $data->total_masuk - $data->total_keluar;

        return response()->json([
            'data' => [
            'saldo' => $saldo,
            'masuk' => $data->total_masuk,
            'keluar' => $data->total_keluar,
            'success' => true,
            'message' => 'Saldo bulan ini berhasil diambil'
            ],
        ]);
    }
}
