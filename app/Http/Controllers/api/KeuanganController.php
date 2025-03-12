<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\LpjModel;
use App\Models\BeginningModel;
use PDF;
use Carbon\Carbon;

class KeuanganController extends Controller
{
    public function insLpj (Request $request)
    {
        // dd($request->all());

        $insKeuangan = LpjModel::create([
            'id_lpj' => str::uuid(),
            'tgl_transaksi' => $request->tgl_transaksi,
            'deskripsi' => $request->deskripsi,
            'jenis' => $request->jenis,
            'nominal' => $request->nominal,
            'pic' => $request->pic,
            'keterangan' => $request->keterangan,
            'input_by' => $request->user()->name,
        ]);

        if ($insKeuangan) {
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

    public function listLpj (Request $request)
    {
        // dd($request->all());
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $jenis = $request->jenis;

        $listJenis = [];

        if ($jenis == 'All') {
            $listJenis = ['Masuk', 'Keluar'];
        } else {
            $listJenis = [$jenis];
        }

        $datas = LpjModel::whereBetween('tgl_transaksi',[$tgl_awal, $tgl_akhir])->whereIn('jenis',$listJenis)
            ->where(function ($q) use ($search) {
                $q
                    ->where('tgl_transaksi', 'like', '%' . $search . '%')
                    ->orwhere('deskripsi', 'like', '%' . $search . '%')
                    ->orwhere('jenis', 'like', '%' . $search . '%')
                    ->orwhere('nominal', 'like', '%' . $search . '%')
                    ->orwhere('pic', 'like', '%' . $search . '%');
            })
            ->orderBy('tgl_transaksi', 'asc')
            ->skip($start)
            ->take($length)
            ->get();

        $count = LpjModel::whereBetween('tgl_transaksi',[$tgl_awal, $tgl_akhir])->whereIn('jenis',$listJenis)
        ->where(function ($q) use ($search) {
            $q
                ->where('tgl_transaksi', 'like', '%' . $search . '%')
                ->orwhere('deskripsi', 'like', '%' . $search . '%')
                ->orwhere('jenis', 'like', '%' . $search . '%')
                ->orwhere('nominal', 'like', '%' . $search . '%')
                ->orwhere('pic', 'like', '%' . $search . '%');
        })
            ->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $datas,
        ];
    }

    public function cetakLpj ($tgl_awal, $tgl_akhir)
    {
        // $tgl_awal = $tgl_awal ?? '2025-02-01';
        $bulanSebelumnya = Carbon::parse($tgl_awal)->subMonth()->format('F Y'); // Contoh: "January 2025"
        // Ambil saldo awal
    $saldoAwal = BeginningModel::where('periode', $tgl_awal)->value('nominal') ?? 0;

    // Ambil transaksi dalam rentang tanggal
    $transaksi = LpjModel::whereBetween('tgl_transaksi', [$tgl_awal, $tgl_akhir])
        ->orderBy('tgl_transaksi')
        ->get();

    // Hitung saldo berjalan
    $saldo = $saldoAwal;
    $dataTransaksi = [];

    // Tambahkan saldo awal ke array
    $dataTransaksi[] = [
        'tgl_transaksi' => $tgl_awal,
        'deskripsi' => "End of $bulanSebelumnya",
        'masuk' => 0,
        'keluar' => 0,
        'saldo' => number_format($saldoAwal, 2, ',', '.') // Format angka
    ];

    // Loop transaksi untuk hitung saldo
    foreach ($transaksi as $item) {
        $masuk = $item->jenis === 'Masuk' ? $item->nominal : 0;
        $keluar = $item->jenis === 'Keluar' ? $item->nominal : 0;
        $saldo += $masuk - $keluar;

        $dataTransaksi[] = [
            'tgl_transaksi' => $item->tgl_transaksi,
            'deskripsi' => $item->deskripsi,
            'masuk' => number_format($masuk, 2, ',', '.'),
            'keluar' => number_format($keluar, 2, ',', '.'),
            'saldo' => number_format($saldo, 2, ',', '.')
        ];
    }

    // Generate PDF tanpa menyimpan
    $pdf = Pdf::loadView('keuangan.pdf_lpj', compact('dataTransaksi', 'tgl_awal', 'tgl_akhir'));

    return $pdf->stream('Laporan_LPJ.pdf'); // Tampilkan langsung di browser
    }
}
