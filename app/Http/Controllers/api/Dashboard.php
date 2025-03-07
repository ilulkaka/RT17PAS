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

        $mcfnow = date('Y-m-d', strtotime('1 days'));
        //TODO:get sales from MCFrame
        $sales =0;
        $acp9909 = DB::select(
            "SELECT ISNULL((SUM(finish_qty)),0)as qty from tb_hasil_produksi WHERE tgl_proses BETWEEN '$awal' and '$now' and line_proses = 320 "
        );
        $lembur =0;
        $mesinstop = $this->mesinstop();
        $marqueExpres = DB::select("SELECT b.tag, b.warna_tag, COUNT(b.warna_tag)as tot, b.finish from
        (SELECT lot_no from tb_next_process WHERE status_wip = 'In')a 
        LEFT JOIN 
        (select lot_no, tag, warna_tag, finish from tb_workresult WHERE tag = LOWER('EXPRES'))b on a.lot_no = b.lot_no
        WHERE b.tag is not NULL
        GROUP BY b.tag, b.warna_tag, b.finish");

        return [
            'success' => true,
            'salespcs' => $sales,
            'acp9909' => $acp9909[0]->qty,
            'lembur' => $lembur,
            'mesinstop' => $mesinstop,
            'marqueexpres' => $marqueExpres,
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
