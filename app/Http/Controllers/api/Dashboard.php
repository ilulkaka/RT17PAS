<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dashboard extends Controller
{
    public function get_dataDashboard(Request $request){
        $tanggal = date('Y-m');
        $awal = $tanggal . '-01';
        $now = date('Y-m-d');
        $tahun = date('Y');
        $tgl_now = date('d');

        $saldo = DB::select("SELECT 
        (COALESCE(a.nominal, 0) + COALESCE(b.masuk, 0) - COALESCE(c.keluar, 0)) AS saldo
            FROM 
        (SELECT nominal FROM tb_beginning LIMIT 1) a
            LEFT JOIN 
        (SELECT SUM(nominal) AS masuk FROM tb_lpj WHERE jenis = 'Masuk') b ON 1=1
            LEFT JOIN 
        (SELECT SUM(nominal) AS keluar FROM tb_lpj WHERE jenis = 'Keluar') c ON 1=1");

        $wargaTerdaftar = DB::table('tb_warga')
            ->where('status_warga', 'Terdaftar')
            ->count();

        $pemasukanBulanIni = DB::select("SELECT SUM(nominal) AS total_masuk
            FROM tb_lpj
            WHERE jenis = 'Masuk'
            AND MONTH(tgl_transaksi) = MONTH(CURRENT_DATE)
            AND YEAR(tgl_transaksi) = YEAR(CURRENT_DATE)");

        $pengeluaranBulanIni = DB::select("SELECT SUM(nominal) AS total_keluar
            FROM tb_lpj
            WHERE jenis = 'Keluar'
            AND MONTH(tgl_transaksi) = MONTH(CURRENT_DATE)
            AND YEAR(tgl_transaksi) = YEAR(CURRENT_DATE)");

        return [
            'success' => true,
            'saldo' => $saldo[0]->saldo,
            'pemasukanBulanIni' => $pemasukanBulanIni[0]->total_masuk,
            'pengeluaranBulanIni' => $pengeluaranBulanIni[0]->total_keluar,
            'wargaTerdaftar' => $wargaTerdaftar,
        ];
    }

    public function mesinstop()
    {
        $Datas = DB::connection('sqlsrv')
            ->table('tb_perbaikan')
            ->leftJoin(
                'v_jam_kerusakan',
                'v_jam_kerusakan.no_perbaikan',
                '=',
                'tb_perbaikan.no_perbaikan'
            )
            ->select(
                'tb_perbaikan.tanggal_rusak',
                'tb_perbaikan.no_perbaikan',
                'tb_perbaikan.departemen',
                'tb_perbaikan.no_induk_mesin',
                'tb_perbaikan.nama_mesin',
                'tb_perbaikan.no_urut_mesin',
                'tb_perbaikan.masalah',
                'v_jam_kerusakan.jam_rusak'
            )
            ->where('tb_perbaikan.no_induk_mesin', '<>', 'NM')
            ->where('tb_perbaikan.klasifikasi', 'C')
            ->whereNotIn('tb_perbaikan.status', ['complete', 'selesai'])
            ->orderBy('tb_perbaikan.tanggal_rusak', 'asc')
            ->take(5)
            ->get();

        return $Datas;
    }

    public function get_laporproduksi(){
        $tanggal = date('Y-m');
        $awal = $tanggal . '-01';
        $now = date('Y-m-d');
        $tahun = date('Y');
        $tgl_now = date('d');

        $pivotedData = DB::select(
            'select * from v_rpt_produksi order by line_proses -1'
        );

        // dd($pivotedData);

        // Inisialisasi array dengan tanggal dari 1 hingga saat ini
        $allDates = [];
        for ($i = 1; $i <= $tgl_now; $i++) {
            $allDates[] = $tanggal . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        // Process the data to create a pivot table
        $pivotTable = [];
        $currentDate = date('Y-m-d');
        foreach ($pivotedData as $row) {
            // $lineProses = $row->line_proses;
            $lineProses = $row->nama_line;
            $tglProses = $row->tgl_proses;
            $finishQty = $row->finish_qty;
            $targetQty = $row->target_qty ?? 0;
            $targetMonth = $row->target_month ?? 0;

            if (!isset($pivotTable[$lineProses])) {
                $pivotTable[$lineProses] = [
                    'target_qty' => array_fill_keys($allDates, 0),
                    'finish_qty' => array_fill_keys($allDates, 0),
                    'target_month' => 0,
                    'akumulasi' => 0,
                ];
            }

            $pivotTable[$lineProses]['target_qty'][$tglProses] = $targetQty;
            $pivotTable[$lineProses]['finish_qty'][$tglProses] = $finishQty;
            // $pivotTable[$lineProses]['target_month'] += $targetQty;
            // Update target_month only if the date is less than or equal to the current date
            if ($tglProses <= $currentDate) {
                $pivotTable[$lineProses]['target_month'] += $targetQty;
                $pivotTable[$lineProses]['akumulasi'] += $finishQty;
            }
        }

        // cek setiap baris memiliki semua tanggal
        foreach ($pivotTable as $lineProses => &$data) {
            $data['target_qty'] = array_merge(
                array_fill_keys($allDates, 0),
                $data['target_qty']
            );
            $data['finish_qty'] = array_merge(
                array_fill_keys($allDates, 0),
                $data['finish_qty']
            );
        }

        return response()->json(
            [
                'success' => true,
                'pivotTable' => $pivotTable,
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }
}
