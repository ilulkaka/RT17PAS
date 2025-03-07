<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LineModel;
use App\Models\NGProduksiModel;
use App\Models\TargetModel;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProduksiController extends Controller
{
    public function getline(){
        return [
            "success"=>true,
            "data"=>LineModel::select('nama_line','kode_line')->where('use_flag', '=', 'Y')->orderBy('kode_line','desc')->get(),
        ];
    }

    public function getwarna(){
        return [
            "success"=>true,
            "data"=>DB::table('tb_workresult')->select('warna_tag')->groupBy('warna_tag')->orderBy('warna_tag')->get(),
        ];
    }

    public function g_shikakari(Request $request){
        $periode1 = date('Y-m');
        $periode = $periode1 . '-01';
        $Datas = DB::select("SELECT a.kode_line, f.nama_line, COALESCE(e.target,0)as target, sum(a.qty_in) as qty, COUNT(a.lot_no) as lot, count(b.qty_in)as pendek, count(c.qty_in)as sedang, count(d.qty_in)as panjang from
        (SELECT id_next_process , kode_line, qty_in, lot_no  FROM tb_next_process WHERE kode_line != 0 and status_wip = 'In' )a 
        LEFT JOIN 
        (select id_next_process, qty_in from tb_next_process WHERE qty_in <= 500)b on a.id_next_process = b.id_next_process
        LEFT JOIN 
        (select id_next_process, qty_in from tb_next_process WHERE qty_in BETWEEN  501 and 1000)c on a.id_next_process = c.id_next_process
        LEFT JOIN 
        (select id_next_process, qty_in from tb_next_process WHERE qty_in >= 1001)d on a.id_next_process = d.id_next_process
        LEFT JOIN 
        (select process_name, target from tb_target WHERE process_cd = 'Shikakari' and periode = ?)e ON a.kode_line = e.process_name
        LEFT JOIN 
        (select kode_line, nama_line from tb_line)f on a.kode_line = f.kode_line
        GROUP BY a.kode_line, e.target, f.nama_line
        ORDER BY a.kode_line asc",[$periode]);

        return ['g_shikakari' => $Datas];
    }

    public function g_hasilproduksi(Request $request){
        $tgl_1 = $request->tgl_1;
        $tgl_2 = $request->tgl_2;
        $select_line = $request->select_line;

        $periode1 = date('Y-m', strtotime($tgl_1));
        $periode = $periode1 . '-01';

        $Datas = DB::select(
            "select * from v_rpt_produksi where line_proses = ?  order by tgl_proses asc",[$select_line]
        );

        return ['g_hasilProduksi' => $Datas];
    }

    public function get_successrate(Request $request){
        $awal = $request['tgl_awal'];
        $now = $request['tgl_akhir'];
        $yd = DB::select(
            "select sum(start_qty) as start, sum(finish_qty) as finish from tb_hasil_produksi where tgl_proses >= '$awal' and tgl_proses <= '$now' and line_proses = '320'"
        );
        $kensa = $yd[0]->finish;
        $zoukei = $yd[0]->start;

        if ($zoukei > 0) {
            $persen1 = ($kensa / $zoukei) * 100;
        } else {
            $persen1 = 0;
        }
        $persen = number_format($persen1, 2);

        $target_sr = DB::table('tb_target')
            ->select('target')
            ->where('process_cd', '=', 'Success Rate')
            ->where('process_name', '=', 'All')
            ->where('periode', $awal)
            ->get();

        return [
            'success' => true,
            'persen' => $persen,
            'target_sr' => $target_sr[0]->target,
        ];
    }

    public function listng(Request $request)
    {
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $Datas = DB::table('tb_master_ng_produksi')
            ->skip($start)
            ->take($length)
            ->get();
        $count = DB::table('tb_master_ng_produksi')->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $Datas,
        ];
    }

    public function inquery_report_detail(Request $request)
    {
        //dd($request->all());
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');

        $line_proses1 = $request->input('selectline');
        //dd($line_proses1);
        //$datass = DB::table('tb_hasil_produksi')->get();
        $datass = DB::select(
            'select line_proses from tb_hasil_produksi group by line_proses'
        );
        $listdatass = [];
        //dd($datass);

        if ($line_proses1 == 'All') {
            foreach ($datass as $key) {
                array_push($listdatass, $key->line_proses);
            }
        } else {
            array_push($listdatass, $line_proses1);
        }

        // dd($listdatass);

        $Datas = DB::table('tb_hasil_produksi as a')
            ->leftJoin('tb_line as b', 'a.line_proses', '=', 'b.kode_line')
            ->select(
                'a.id_hasil_produksi',
                'b.nama_line as line1',
                'a.tgl_proses',
                'a.part_no',
                'a.lot_no',
                'a.incoming_qty',
                'a.finish_qty',
                'a.ng_qty',
                'a.operator',
                'a.shift',
                'a.total_cycle',
                'a.shape',
                'a.no_mesin',
                DB::raw(
                    '(CASE WHEN a.incoming_qty > 0 THEN (a.finish_qty / a.incoming_qty * 100) ELSE 0 END) as pro'
                ),
                'a.ukuran_haba',
                'a.remark'
            )
            ->whereBetween('a.tgl_proses', [$tgl_awal, $tgl_akhir])
            ->whereIn('a.line_proses', $listdatass)
            ->where(function ($q) use ($search) {
                $q
                    ->where('a.part_no', 'like', '%' . $search . '%')
                    ->orwhere('a.lot_no', 'like', '%' . $search . '%');
            })
            ->orderBy('a.tgl_proses', 'asc')
            ->skip($start)
            ->take($length)
            ->get();

        $count = DB::table('tb_hasil_produksi as a')
            ->leftJoin('tb_line as b', 'a.line_proses', '=', 'b.kode_line')
            ->select(
                'a.id_hasil_produksi',
                'b.nama_line as line1',
                'a.tgl_proses',
                'a.part_no',
                'a.lot_no',
                'a.incoming_qty',
                'a.finish_qty',
                'a.ng_qty',
                'a.operator',
                'a.shift',
                'a.total_cycle',
                'a.shape',
                'a.no_mesin',
                DB::raw(
                    '(CASE WHEN a.incoming_qty > 0 THEN (a.finish_qty / a.incoming_qty * 100) ELSE 0 END) as pro'
                ),
                'a.ukuran_haba',
                'a.remark'
            )
            ->whereBetween('a.tgl_proses', [$tgl_awal, $tgl_akhir])
            ->whereIn('a.line_proses', $listdatass)
            ->where(function ($q) use ($search) {
                $q
                    ->where('a.part_no', 'like', '%' . $search . '%')
                    ->orwhere('a.lot_no', 'like', '%' . $search . '%');
            })
            ->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $Datas,
        ];
    }

    public function detail_ng(Request $request)
    {
        //dd($request->all());
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $id_hasil_produksi = $request->input('id_hasil_produksi');

        $Datas = NGProduksiModel::where(
            'id_hasil_produksi',
            '=',
            $id_hasil_produksi
        )
            ->skip($start)
            ->take($length)
            ->get();
        $count = NGProduksiModel::where(
            'id_hasil_produksi',
            '=',
            $id_hasil_produksi
        )->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $Datas,
        ];
    }

    public function get_excel_hasilproduksi(Request $request)
    {
        // dd($request->all());
        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');
        $line_proses1 = $request->input('selectline');

        if ($line_proses1 == 'All') {
            $Datas = DB::table('tb_hasil_produksi as a')
                ->leftJoin('tb_line as b', 'a.line_proses', '=', 'b.kode_line')
                ->leftJoin(
                    'tb_ng_produksi as c',
                    'a.id_hasil_produksi',
                    '=',
                    'c.id_hasil_produksi'
                )
                ->leftJoin('tb_next_process as d', function ($join) {
                    $join
                        ->on('a.line_proses', '=', 'd.kode_line')
                        ->whereColumn('a.lot_no', '=', 'd.lot_no');
                })
                ->select(
                    'a.id_hasil_produksi',
                    DB::raw('(b.nama_line) as line1'),
                    'a.part_no',
                    'a.lot_no',
                    'a.incoming_qty',
                    'a.finish_qty',
                    'a.ng_qty',
                    'a.operator',
                    'a.shift',
                    'a.no_mesin',
                    DB::raw('CASE 
            WHEN a.incoming_qty <> 0 THEN (a.finish_qty / a.incoming_qty * 100) 
            ELSE 0 
        END as pro'),
                    'a.type',
                    'a.crf',
                    'a.created_at',
                    'a.start_qty',
                    'a.tgl_proses',
                    'a.remark',
                    'a.cycle',
                    'a.total_cycle',
                    DB::raw("STUFF((SELECT ', ' + CONCAT(c2.ng_type, ':', c2.ng_qty) 
            FROM tb_ng_produksi c2 
            WHERE c2.id_hasil_produksi = a.id_hasil_produksi 
            FOR XML PATH('')), 1, 2, '') as ng_data"),
                    'd.tgl_in'
                )
                ->where('a.tgl_proses', '>=', $tgl_awal)
                ->where('a.tgl_proses', '<=', $tgl_akhir)
                ->groupBy(
                    'a.id_hasil_produksi',
                    'b.nama_line',
                    'a.part_no',
                    'a.lot_no',
                    'a.incoming_qty',
                    'a.finish_qty',
                    'a.ng_qty',
                    'a.operator',
                    'a.shift',
                    'a.no_mesin',
                    'a.type',
                    'a.crf',
                    'a.created_at',
                    'a.start_qty',
                    'a.tgl_proses',
                    'a.remark',
                    'a.cycle',
                    'a.total_cycle',
                    'd.tgl_in'
                )
                ->get();
        } else {
            $Datas = DB::table('tb_hasil_produksi as a')
                ->leftJoin('tb_line as b', 'a.line_proses', '=', 'b.kode_line')
                ->leftJoin(
                    'tb_ng_produksi as c',
                    'a.id_hasil_produksi',
                    '=',
                    'c.id_hasil_produksi'
                )
                ->leftJoin('tb_next_process as d', function ($join) {
                    $join
                        ->on('a.line_proses', '=', 'd.kode_line')
                        ->whereColumn('a.lot_no', '=', 'd.lot_no');
                })
                ->select(
                    'a.id_hasil_produksi',
                    DB::raw('(b.nama_line) as line1'),
                    'a.part_no',
                    'a.lot_no',
                    'a.incoming_qty',
                    'a.finish_qty',
                    'a.ng_qty',
                    'a.operator',
                    'a.shift',
                    'a.no_mesin',
                    DB::raw('CASE 
            WHEN a.incoming_qty <> 0 THEN (a.finish_qty / a.incoming_qty * 100) 
            ELSE 0 
        END as pro'),
                    'a.type',
                    'a.crf',
                    'a.created_at',
                    'a.start_qty',
                    'a.tgl_proses',
                    'a.remark',
                    'a.cycle',
                    'a.total_cycle',
                    DB::raw("STUFF((SELECT ', ' + CONCAT(c2.ng_type, ':', c2.ng_qty) 
            FROM tb_ng_produksi c2 
            WHERE c2.id_hasil_produksi = a.id_hasil_produksi 
            FOR XML PATH('')), 1, 2, '') as ng_data"),
                    'd.tgl_in'
                )
                ->where('a.tgl_proses', '>=', $tgl_awal)
                ->where('a.tgl_proses', '<=', $tgl_akhir)
                ->where('a.line_proses', $line_proses1)
                ->groupBy(
                    'a.id_hasil_produksi',
                    'b.nama_line',
                    'a.part_no',
                    'a.lot_no',
                    'a.incoming_qty',
                    'a.finish_qty',
                    'a.ng_qty',
                    'a.operator',
                    'a.shift',
                    'a.no_mesin',
                    'a.type',
                    'a.crf',
                    'a.created_at',
                    'a.start_qty',
                    'a.tgl_proses',
                    'a.remark',
                    'a.cycle',
                    'a.total_cycle',
                    'd.tgl_in'
                )
                ->get();
        }

        // dd($Datas);

        if($Datas->isEmpty()){
            return ['message' => 'No Data', 'success' => false];
        } else {
            // Retrieve unique ng_type
            $uniqueNgTypes = [];
            foreach ($Datas as $data) {
                if ($data->ng_data) {
                    foreach (explode(', ', $data->ng_data) as $ng) {
                        $ngParts = explode(':', $ng);
                        if (count($ngParts) == 2) {
                            $type = $ngParts[0];
                            if (!in_array($type, $uniqueNgTypes)) {
                                $uniqueNgTypes[] = $type;
                            }
                        }
                    }
                }
            }

            // Set static headers
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'NO');
            $sheet->setCellValue('B1', 'PROSES');
            $sheet->setCellValue('C1', 'TYPE');
            $sheet->setCellValue('D1', 'CRF');
            $sheet->setCellValue('E1', 'PART NO');
            $sheet->setCellValue('F1', 'LOT NO');
            $sheet->setCellValue('G1', 'START QTY');
            $sheet->setCellValue('H1', 'INCOMING');
            $sheet->setCellValue('I1', 'FINISH');
            $sheet->setCellValue('J1', 'NG');
            $sheet->setCellValue('K1', 'PROSENTASE');
            $sheet->setCellValue('L1', 'OPERATOR');
            $sheet->setCellValue('M1', 'SHIFT');
            $sheet->setCellValue('N1', 'MESIN NO');
            $sheet->setCellValue('O1', 'PROSES DATE');
            $sheet->setCellValue('P1', 'REMARK');
            $sheet->setCellValue('Q1', 'CYCLE');
            $sheet->setCellValue('R1', 'TOT CYCLE');
            $sheet->setCellValue('S1', 'Created_Date');
            $sheet->setCellValue('T1', 'IN');

            // Set dynamic ng_type headers
            $col = 'U';
            foreach ($uniqueNgTypes as $ngType) {
                $sheet->setCellValue($col . '1', $ngType);
                $col++;
            }

            // Fill in data
            $line = 2;
            $no = 1;
            foreach ($Datas as $data) {
                $sheet->setCellValue('A' . $line, $no++);
                $sheet->setCellValue('B' . $line, $data->line1);
                $sheet->setCellValue('C' . $line, $data->type);
                $sheet->setCellValue('D' . $line, $data->crf);
                $sheet->setCellValue('E' . $line, $data->part_no);
                $sheet->setCellValue('F' . $line, $data->lot_no);
                $sheet->setCellValue('G' . $line, $data->start_qty);
                $sheet->setCellValue('H' . $line, $data->incoming_qty);
                $sheet->setCellValue('I' . $line, $data->finish_qty);
                $sheet->setCellValue('J' . $line, $data->ng_qty);
                $sheet->setCellValue('K' . $line, $data->pro);
                $sheet->setCellValue('L' . $line, $data->operator);
                $sheet->setCellValue('M' . $line, $data->shift);
                $sheet->setCellValue('N' . $line, $data->no_mesin);
                $sheet->setCellValue('O' . $line, $data->tgl_proses);
                $sheet->setCellValue('P' . $line, $data->remark);
                $sheet->setCellValue('Q' . $line, $data->cycle);
                $sheet->setCellValue('R' . $line, $data->total_cycle);
                $sheet->setCellValue('S' . $line, $data->created_at);
                $sheet->setCellValue('T' . $line, $data->tgl_in);

                // Fill ng_type and ng_qty values
                $ngData = [];
                if ($data->ng_data) {
                    foreach (explode(', ', $data->ng_data) as $ng) {
                        $ngParts = explode(':', $ng);
                        if (count($ngParts) == 2) {
                            $ngData[$ngParts[0]] = $ngParts[1];
                        }
                    }
                }
                $col = 'U';
                foreach ($uniqueNgTypes as $ngType) {
                    $sheet->setCellValue(
                        $col . $line,
                        isset($ngData[$ngType]) ? $ngData[$ngType] : ''
                    );
                    $col++;
                }
                $line++;
            }

            $writer = new Xlsx($spreadsheet);
            $filename = 'Rekap_Hasil_Produksi.xlsx';
            $writer->save(public_path('storage/excel/' . $filename));
            return ['file' => url('/') . '/storage/excel/' . $filename,
            'success' => true,
        ];
        }
        
    }

    public function l_approve_jam_operator(Request $request)
    {
        // dd($request->all());

        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');
        $selectshift = $request->input('selectshift');
        $line = $request->input('selectline');
        $status = $request->input('selectstatus');

        $datass = DB::table('tb_jam_kerja')
            ->select('line_proses', 'shift')
            ->where('line_proses', '!=', '999')
            ->groupBy('line_proses', 'shift')
            ->get();
        $listdatass = [];

        if ($line == 'All') {
            foreach ($datass as $key) {
                array_push($listdatass, $key->line_proses);
            }
        } else {
            foreach ($datass as $key) {
                array_push($listdatass, $line);
            }
        }

        $listshift = [];
        if ($selectshift == 'All') {
            foreach ($datass as $key) {
                array_push($listshift, $key->shift);
            }
        } else {
            foreach ($datass as $key) {
                array_push($listshift, $selectshift);
            }
        }

        $liststatus = [];

        if ($status == 'All') {
            $liststatus = ['Approve', 'Pending'];
        } else {
            $liststatus = [$status];
        }

        $Datas = db::table('tb_jam_kerja as a')
            ->leftJoin('tb_line as b', 'a.line_proses', '=', 'b.kode_line')
            ->select(
                DB::Raw(
                    'a.id_jam_kerja, a.tgl_jam_kerja, a.operator, b.nama_line, a.shift, a.jam_total, a.jam_lain, a.ket, a.status, a.approve, a.tgl_approve'
                )
            )
            ->whereBetween('a.tgl_jam_kerja', [$tgl_awal,$tgl_akhir])
            ->whereIn('a.line_proses', $listdatass)
            ->whereIn('a.shift', $listshift)
            ->whereIn('a.status', $liststatus)
            ->where(function ($q) use ($search) {
                $q
                    ->where('a.operator', 'like', '%' . $search . '%')
                    ->orwhere('a.line_proses', 'like', '%' . $search . '%');
            })
            ->orderBy('a.created_at', 'asc')
            ->skip($start)
            ->take($length)
            ->get();

        $count = db::table('tb_jam_kerja as a')
                ->leftJoin('tb_line as b', 'a.line_proses', '=', 'b.kode_line')
                ->select(
                    DB::Raw(
                        'a.id_jam_kerja, a.tgl_jam_kerja, a.operator, b.nama_line, a.shift, a.jam_total, a.jam_lain, a.ket, a.status, a.approve, a.tgl_approve'
                    )
                )
                ->whereBetween('a.tgl_jam_kerja', [$tgl_awal,$tgl_akhir])
                ->whereIn('a.line_proses', $listdatass)
                ->whereIn('a.shift', $listshift)
                ->whereIn('a.status', $liststatus)
            ->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $Datas,
        ];
    }

    public function l_approve_jam_operator_1(Request $request){
        // dd($request->all());
        $token = apache_request_headers();
        $user = UserModel::where(
            'api_token',
            base64_decode($token['token_req'])
        )->first();
        $dept = $user->departemen;

        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');

        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $selectshift = $request->input('selectshift_1');
        $line = $request->input('selectline_1');

        $datass = DB::table('tb_jam_kerja')
            ->select('line_proses', 'shift')
            ->groupBy('line_proses', 'shift')
            ->get();
        $listdatass = [];

        if ($line == 'All') {
            foreach ($datass as $key) {
                array_push($listdatass, $key->line_proses);
            }
        } else {
            foreach ($datass as $key) {
                array_push($listdatass, $line);
            }
        }
        //dd($listdatass);

        $Datas = db::table('tb_jam_kerja as a')
            ->leftJoin('tb_line as b', 'a.line_proses', '=', 'b.kode_line')
            ->select(
                DB::Raw(
                    'a.id_jam_kerja, a.tgl_jam_kerja, a.operator, b.nama_line, a.shift, a.jam_total, a.jam_lain, a.ket, a.status, a.approve, a.tgl_approve'
                )
            )
            ->where('a.tgl_jam_kerja', '>=', $tgl_awal)
            ->where('a.tgl_jam_kerja', '<=', $tgl_akhir)
            ->whereIn('a.line_proses', $listdatass)
            ->where('a.status', '=', 'Approve')
            ->where(function ($q) use ($search) {
                $q
                    ->where('a.operator', 'like', '%' . $search . '%')
                    ->orwhere('a.line_proses', 'like', '%' . $search . '%');
            })
            ->orderBy('a.created_at', 'asc')
            ->skip($start)
            ->take($length)
            ->get();

        $count = db::table('tb_jam_kerja as a')
            ->leftJoin('tb_line as b', 'a.line_proses', '=', 'b.kode_line')
            ->select(
                DB::Raw(
                    'a.id_jam_kerja, a.tgl_jam_kerja, a.operator, b.nama_line, a.shift, a.jam_total, a.jam_lain, a.ket, a.status, a.approve, a.tgl_approve'
                )
            )
            ->where('a.tgl_jam_kerja', '>=', $tgl_awal)
            ->where('a.tgl_jam_kerja', '<=', $tgl_akhir)
            ->whereIn('a.line_proses', $listdatass)
            ->where('a.status', '=', 'Approve')
            ->where(function ($q) use ($search) {
                $q
                    ->where('a.operator', 'like', '%' . $search . '%')
                    ->orwhere('a.line_proses', 'like', '%' . $search . '%');
            })
            ->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $Datas,
        ];
    }

    public function edit_jamkerjaoperator(Request $request)
    {
        // dd($request->all());    

        $idjam = $request->input('id_jam_kerja');
        $jamtotal = $request->input('e-jamtotal');
        $ket = $request->input('e-keterangan');
        //$req = DB::table('tb_jam_kerja')->where('id_jam_kerja','=',$idjam)->first();
        //$req = JamhasiloperatorModel::find($idjam);

        $lineproses = DB::select(
            "select line_proses from tb_jam_kerja where id_jam_kerja = '$idjam'"
        );
        $lineproses1 = $lineproses[0]->line_proses;

        $kodeline = DB::select(
            "select dept_section from tb_line where kode_line = '$lineproses1'"
        );
        $kodeline1 = $kodeline[0]->dept_section;

        if($request->user()->hasDepartment($kodeline1) == true ){
            $req = DB::table('tb_jam_kerja')
            ->where('id_jam_kerja', '=', $idjam)
            ->update([
                'jam_total' => $jamtotal,
                'ket' => $ket,
            ]);

        return response()->json ([
            'message' => 'Update Total Jam Operator Success .',
            'success' => true,
        ],200);
        } else {
            return response()->json ([
                'message' => 'Update Gagal, Access denied .',
                'success' => false,
            ],403);
        }
    }

    public function approve_jam_kerja(Request $request)
    {
        // dd($request->all());

        $idjam = $request->input('id_jam_kerja');

        $lineproses = DB::select(
            "select line_proses from tb_jam_kerja where id_jam_kerja = '$idjam'"
        );
        $lineproses1 = $lineproses[0]->line_proses;

        $kodeline = DB::select(
            "select dept_section from tb_line where kode_line = '$lineproses1'"
        );
        $kodeline1 = $kodeline[0]->dept_section;

        if($request->user()->hasDepartment($kodeline1) == true ){
            $req = DB::table('tb_jam_kerja')
                ->where('id_jam_kerja', '=', $idjam)
                ->update([
                    'approve' => $request->user()->name,
                    'status' => 'Approve',
                    'tgl_approve' => date('Y-m-d'),
                ]);

            return [
                'message' => 'Update Success .',
                'success' => true,
            ];
        } else {
            return [
                'message' => 'Approve Gagal, Karna Bukan Operator Anda .',
                'success' => false,
            ];
        }
    }

    public function grafik_opr_finish_qty (Request $request){
        // dd($request->all());

        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');
        $selectline = $request->input('selectline');

        $hasil_atari = DB::select("SELECT t1.operator, isnull((t1.finish_qty),0) as finish_qty, isnull((t7.jam_total),0) as jam_total, isnull((t1.finish_qty / t7.jam_total),0) as pcsjam, isnull((t1.total_cycle),0) as total_cycle, isnull((t1.total_cycle / t7.jam_total),0) as cyclejam  from
        (SELECT nik, operator, sum(finish_qty) as finish_qty, sum(total_cycle) as total_cycle from tb_hasil_produksi WHERE line_proses = ? and tgl_proses between  ? and  ? GROUP BY nik, operator)t1
        left JOIN 
        (select nik, operator, sum(jam_total)as jam_total from tb_jam_kerja tjk WHERE tgl_jam_kerja between  ? and  ?  and line_proses = ? and status = 'Approve' GROUP by nik, operator)t7 on t1.nik = t7.nik order by t1.finish_qty desc",[$selectline, $tgl_awal, $tgl_akhir, $tgl_awal, $tgl_akhir, $selectline ]);
        //dd($hasil_atari);

        return ['hasil_atari' => $hasil_atari];
    }

    public function detail_hasil_jam_opr (Request $request){
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');
        $selectline = $request->input('selectline');

        $Datas = DB::select(
            "SELECT t1.operator, isnull((t1.finish_qty),0) as finish_qty, isnull((t7.jam_total),0) as jam_total, isnull((t1.finish_qty / t7.jam_total),0) as pcsjam, isnull((t1.total_cycle),0) as total_cycle, isnull((t1.total_cycle / t7.jam_total),0) as cyclejam  from
        (SELECT nik, operator, sum(finish_qty) as finish_qty, sum(total_cycle) as total_cycle from tb_hasil_produksi WHERE line_proses = ? and tgl_proses between ? and ? GROUP BY nik, operator)t1
        left JOIN 
        (select nik, operator, sum(jam_total)as jam_total from tb_jam_kerja tjk WHERE tgl_jam_kerja between ? and ? and line_proses = ? GROUP by nik, operator)t7 on t1.nik = t7.nik order by t1.finish_qty desc OFFSET " .
                $start .
                ' ROWS FETCH NEXT ' .
                $length .
                ' ROWS ONLY', [$selectline, $tgl_awal, $tgl_akhir, $tgl_awal, $tgl_akhir, $selectline ]
        );

        $count = DB::select("select count(*) as total from (SELECT t1.operator, isnull((t1.finish_qty),0) as finish_qty, isnull((t7.jam_total),0) as jam_total, isnull((t1.finish_qty / t7.jam_total),0) as pcsjam  from
          (SELECT nik, operator, sum(finish_qty) as finish_qty from tb_hasil_produksi WHERE line_proses = ? and tgl_proses between ? and ? GROUP BY nik, operator)t1
          left JOIN 
          (select nik, operator, sum(jam_total)as jam_total from tb_jam_kerja tjk WHERE tgl_jam_kerja between ? and ? and line_proses = ? GROUP by nik, operator)t7 on t1.nik = t7.nik)a", [$selectline, $tgl_awal, $tgl_akhir, $tgl_awal, $tgl_akhir, $selectline ])[0]
            ->total;

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $Datas,
        ];
    }

    public function detail_hasil_fcr_opr (Request $request){
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');
        $selectline = $request->input('selectline');

        $Datas = DB::select(
            "select a.type, isnull((t1.finish_qty),0) as F, isnull((t2.finish_qty),0) as CR from 
        (select type from tb_hasil_produksi where line_proses = ? and tgl_proses between ? and ? group by type)a
        left join
        (select type, sum(finish_qty)as finish_qty from tb_hasil_produksi where crf='F' and line_proses = ? and tgl_proses between ? and ? group by type)t1 on a.type = t1.type
        left join
        (select type, sum(finish_qty)as finish_qty from tb_hasil_produksi where crf='CR' and line_proses = ? and tgl_proses between ? and ? group by type)t2 on a.type = t2.type order by a.type asc OFFSET " .
                $start .
                ' ROWS FETCH NEXT ' .
                $length .
                ' ROWS ONLY', [$selectline, $tgl_awal, $tgl_akhir, $selectline, $tgl_awal, $tgl_akhir, $selectline, $tgl_awal, $tgl_akhir ]
        );

        $count = DB::select("select count(*)as total from (select t1.type, t1.finish_qty as F, t2.finish_qty as CR from 
        (select type from tb_hasil_produksi where line_proses = ? and tgl_proses between ? and ? group by type)a
        left join
        (select type, sum(finish_qty)as finish_qty from tb_hasil_produksi where crf='F' and line_proses = ? and tgl_proses between ? and ? group by type)t1 on a.type = t1.type
        left join
        (select type, sum(finish_qty)as finish_qty from tb_hasil_produksi where crf='CR' and line_proses = ? and tgl_proses between ? and ? group by type)t2 on a.type = t2.type)a", [$selectline, $tgl_awal, $tgl_akhir, $selectline, $tgl_awal, $tgl_akhir, $selectline, $tgl_awal, $tgl_akhir ])[0]
            ->total;

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $Datas,
        ];
    }

    public function get_rekap_produksiNsales (Request $request){
        $tanggal = date('Y-m');
        $awal = $tanggal . '-01';
        $now = date('Y-m-d');
        $mcfnow = date('Y-m-d', strtotime('1 days'));
        $tgl = $now;

        $datasTarget = TargetModel::select('process_cd','target')
            ->whereIn('process_cd',['produksi','sales'])
            ->where('periode', 'like', $tanggal . '%')
            ->get()->toArray();

        if (empty($datasTarget)) {
            $target = [
                ['process_cd' => 'produksi', 'target' => 0],
                ['process_cd' => 'sales', 'target' => 0],
            ];
        } else {
            $target = $datasTarget;
        }

        $aktualProduksi = DB::table('tb_hasil_produksi')
            ->where('line_proses', '=', 320)
            ->whereBetween('tgl_proses', [$awal, $now])
            ->sum('finish_qty');

        $aktualSales1 = DB::connection('mcframe')->select(
            "SELECT  SUM(B.SLS_QTY) AS QTY FROM HT_URIAGEJ_ALL A , HT_URIAGEH_ALL B WHERE A.CO_CD = 'A300' AND A.SLS_NO = B.SLS_NO AND A.CO_CD = B.CO_CD AND B.CAN_FLG = '0' AND SLS_DT >= ? AND SLS_DT <= ? AND UNIT_CD != 'KGM'", [$awal, $now ]
        );

        $aktualSales = $aktualSales1[0]->qty ?? 0;

        // ====

        // $acp9909 = DB::connection('mcframe')->select(
        //     "SELECT  SUM(ACT_QTY) AS QTY FROM ST_DEKIDAKA_ALL WHERE CO_CD = 'A300' AND ACT_QTY > 0 AND ACT_DEST_DT >= '$awal' AND ACT_DEST_DT <='$mcfnow'"
        // );

        $sales = DB::connection('mcframe')->select("SELECT D.OFCL_NM
                    ,B.CUR_CD
                    ,SUM(B.SLS_QTY) AS QTY
                    ,B.UNIT_CD
                    ,SUM(B.SLS_AMT) AS AMT
                    ,SUM(B.XTX_SLS_AMT) AS AMT_TAX_ENCLUDED
                    FROM HT_URIAGEJ_ALL A
                    ,HT_URIAGEH_ALL B
                    ,HT_JUCHUJ_ALL C
                    ,CM_BP_ALL D
                    WHERE A.CO_CD = 'A300'
                    AND A.SLS_NO = B.SLS_NO
            AND     A.CO_CD = B.CO_CD
            AND     B.CAN_FLG = '0'
            AND     B.SLO_NO = C.SLO_NO(+)
            AND     B.CO_CD = C.CO_CD(+)
            AND     C.IV_TGT_FLG(+) = '1'
            AND     C.CO_CD = D.CO_CD
            AND     C.SCST_CD = D.COMPANY_CD
            AND     A.SLS_DT >= ?
            AND     A.SLS_DT <= ?
            GROUP BY D.OFCL_NM,B.CUR_CD,B.UNIT_CD
            ORDER BY D.OFCL_NM
            ", [$awal, $now]);

            $totpcs = $aktualSales;

        $salesamt = DB::connection('mcframe')
            ->select("SELECT A.CUR_CD, sum(B.SLS_QTY) AS qty, SUM(B.SLS_AMT)AS amt  FROM HT_URIAGEJ_ALL A, HT_URIAGEH_ALL B WHERE A.CO_CD ='A300'
            AND A.SLS_NO = B.SLS_NO
            AND A.SLS_DT >= ?
            AND A.SLS_DT <= ?
            GROUP BY A.CUR_CD
        ", [$awal, $now]);

        $targetproduksi = DB::table('tb_target')
            ->select('process_name', 'target')
            ->where('process_cd', '=', 'Proses')
            ->where('periode', '=', $awal)
            ->get();
        $targetshikakari = DB::table('tb_target')
            ->select('process_name', 'target')
            ->where('process_cd', '=', 'Shikakari')
            ->where('periode', '=', $awal)
            ->get();

        $selectLine = DB::table('tb_line')
            ->select('kode_line', 'nama_line')
            ->where('use_grouping', '=', 'Y')
            ->get();

            return [
                'target' => $target,
                'aktualProduksi' => $aktualProduksi,
                'aktualSales' => $aktualSales,
                'salesproduksi' => $sales,
                'totalsales' => $totpcs,
                'totalamount' => $salesamt,
                // 'acp9909' => $acp9909,
                // 't_sales' => $t_sales,
                'selectLine' => $selectLine,
            ];
    }

    public function get_rekapHasilProduksi (Request $request){
        // dd($request->all());
        $line = $request->line;
        $start = $request->tgl;
        $end = $request->tgl2;

        if ($request->jenis == 'Daily') {
            $hasil_produksi = DB::select(
                "SELECT ab.kode_line, ab.nama_line, ab.target_qty, SUM(c.qty_in)as finish_qty from
                (select a.kode_line, a.nama_line, SUM(b.target) AS target_qty from
                (SELECT kode_line, nama_line FROM tb_line WHERE use_flag = 'Y')a 
                LEFT JOIN 
                (SELECT process_name, target FROM tb_target WHERE process_cd = 'Daily' AND periode BETWEEN ? AND ?)b 
                ON a.kode_line = b.process_name
                GROUP BY a.kode_line, a.nama_line)ab
                LEFT JOIN 
                (SELECT cur_line, qty_in FROM tb_next_process WHERE tgl_in BETWEEN ? AND ?)c
                ON ab.kode_line = c.cur_line
                GROUP BY ab.nama_line, ab.kode_line, ab.target_qty
                ORDER BY ab.kode_line ASC ", [$start, $end, $start, $end ]
            );
        } elseif ($request->jenis == 'Grouping') {
            $hasil_produksi = DB::select("SELECT 
                                ab.inp_code, 
                                ab.inp_code_nm as nama_line, 
                                ab.target_qty, 
                                SUM(xy.qty_in) AS finish_qty  
                                FROM 
                                    (SELECT a.inp_code, a.inp_code_nm, SUM(b.target) AS target_qty 
                                        FROM tb_master_grouping a
                                        LEFT JOIN 
                                            (SELECT grouping_cd, target 
                                                FROM tb_target WHERE 
                                                    process_cd = 'Grouping' and process_name = ?
                                                    AND periode BETWEEN ? AND ?
                                            ) b 
                                        ON 
                                            a.inp_code = b.grouping_cd
                                        GROUP BY 
                                            a.inp_code, 
                                            a.inp_code_nm
                                    ) ab
                                LEFT JOIN 
                                    (SELECT 
                                            y.main_inp_cd, 
                                            SUM(x.qty_in) AS qty_in 
                                        FROM 
                                            (SELECT 
                                                    lot_no, 
                                                    qty_in 
                                                FROM 
                                                    tb_next_process 
                                                WHERE 
                                                    cur_line = ?
                                                    AND tgl_in BETWEEN ? AND ?
                                            ) x
                                        LEFT JOIN 
                                            (SELECT 
                                                    lot_no, 
                                                    main_inp_cd 
                                                FROM 
                                                    tb_workresult 
                                                GROUP BY 
                                                    lot_no, 
                                                    main_inp_cd
                                            ) y 
                                        ON 
                                            x.lot_no = y.lot_no 
                                        GROUP BY 
                                            y.main_inp_cd
                                    ) xy 
                                ON 
                                    ab.inp_code = xy.main_inp_cd
                                GROUP BY 
                                    ab.inp_code, 
                                    ab.inp_code_nm, 
                                    ab.target_qty", [$line, $start, $end, $line, $start, $end,]);
        }

        return [
            'hasilproduksi' => $hasil_produksi,
        ];
    }

    public function list_shikakari (Request $request){
        // dd($request->all());
        // $request->user()->hasDepartment($kodeline1)
        $user = auth()->user();
        $dept = $user->departments->pluck('section')->first();
        
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $line = $request->line;
        $tag = $request->tag;
        $warna = $request->warna;

        $list_line = DB::table('tb_next_process')
            ->select('kode_line')
            ->groupBy('kode_line')
            ->get();

        $c_list = [];
        if ($line == 'All') {
            foreach ($list_line as $key) {
                array_push($c_list, $key->kode_line);
            }
        } else {
            array_push($c_list, $line);
        }

        $list_tag = DB::table('tb_workresult')
            ->select('tag')
            ->groupBy('tag')
            ->get();

        $c_tag = [];
        if ($tag == 'All') {
            foreach ($list_tag as $key) {
                array_push($c_tag, $key->tag);
            }
            $c_tag[] = null;
        } else {
            array_push($c_tag, $tag);
        }

        $list_warna = DB::table('tb_workresult')
            ->select('warna_tag')
            ->groupBy('warna_tag')
            ->get();

        $c_warna = [];
        if ($warna == 'All') {
            foreach ($list_warna as $key) {
                array_push($c_warna, $key->warna_tag);
            }
        } else {
            array_push($c_warna, $warna);
        }

        $Datas = DB::table('tb_next_process as a')
            ->leftJoin('tb_line as b', 'a.kode_line', '=', 'b.kode_line')
            ->leftJoin('tb_line as c', 'a.cur_line', '=', 'c.kode_line')
            ->leftjoin('tb_workresult as d', 'a.lot_no', '=', 'd.lot_no')
            ->select(
                'id_next_process',
                'a.part_no',
                'a.lot_no',
                'a.qty_in',
                'a.tgl_in',
                'a.cur_line',
                'b.nama_line',
                'c.nama_line as cur',
                'd.tag',
                'd.warna_tag',
                DB::raw("'$dept' as dept_section")
            )
            ->where('a.status_wip', '=', 'In')
            ->whereIn('a.kode_line', $c_list)
            ->when($tag == 'All', function ($query) use ($c_tag) {
                return $query->where(function ($query) use ($c_tag) {
                    $query->whereIn('d.tag', $c_tag)->orWhereNull('d.tag');
                });
            })
            ->when($tag != 'All' && $tag != 'Null', function ($query) use (
                $tag
            ) {
                return $query->where('d.tag', '=', $tag);
            })
            ->when($tag == 'Null', function ($query) {
                return $query->whereNull('d.tag');
            })
            ->whereIn('d.warna_tag', $c_warna)
            ->where(function ($q) use ($search) {
                $q
                    ->where('a.part_no', 'like', '%' . $search . '%')
                    ->orwhere('a.lot_no', 'like', '%' . $search . '%');
            })
            ->orderBy('a.part_no', 'asc', 'a.tgl_in', 'asc')
            ->skip($start)
            ->take($length)
            ->get();

        // dd($Datas);

        $count = DB::table('tb_next_process as a')
            ->leftJoin('tb_line as b', 'a.kode_line', '=', 'b.kode_line')
            ->leftJoin('tb_line as c', 'a.cur_line', '=', 'c.kode_line')
            ->leftjoin('tb_workresult as d', 'a.lot_no', '=', 'd.lot_no')
            ->select(
                'id_next_process',
                'a.part_no',
                'a.lot_no',
                'a.qty_in',
                'a.tgl_in',
                'a.cur_line',
                'b.nama_line',
                'c.nama_line as cur',
                'd.tag',
                'd.warna_tag',
                DB::raw("'$dept' as dept_section")
            )
            ->where('a.status_wip', '=', 'In')
            ->whereIn('a.kode_line', $c_list)
            ->when($tag == 'All', function ($query) use ($c_tag) {
                return $query->where(function ($query) use ($c_tag) {
                    $query->whereIn('d.tag', $c_tag)->orWhereNull('d.tag');
                });
            })
            ->when($tag != 'All' && $tag != 'Null', function ($query) use (
                $tag
            ) {
                return $query->where('d.tag', '=', $tag);
            })
            ->when($tag == 'Null', function ($query) {
                return $query->whereNull('d.tag');
            })
            ->whereIn('d.warna_tag', $c_warna)
            ->where(function ($q) use ($search) {
                $q
                    ->where('a.part_no', 'like', '%' . $search . '%')
                    ->orwhere('a.lot_no', 'like', '%' . $search . '%');
            })
            ->count();

                return [
                    'draw' => $draw,
                    'recordsTotal' => $count,
                    'recordsFiltered' => $count,
                    'data' => $Datas,
                ];
    }

    public function excel_shikakari (Request $request){
        $Datas = DB::table('tb_next_process as a')
            ->leftJoin('tb_line as b', 'a.kode_line', '=', 'b.kode_line')
            ->leftJoin('tb_line as c', 'a.cur_line', '=', 'c.kode_line')
            ->leftjoin('tb_workresult as d', 'a.lot_no', '=', 'd.lot_no')
            ->select(
                'id_next_process',
                'a.part_no',
                'a.lot_no',
                'a.qty_in',
                'a.tgl_in',
                'a.cur_line',
                'b.nama_line',
                'c.nama_line as cur',
                'd.tag',
                'd.warna_tag'
            )
            ->where('a.status_wip', '=', 'In')
            ->orderBy('a.part_no', 'asc', 'a.tgl_in', 'asc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'Proses Sebelum');
        $sheet->setCellValue('C1', 'Proses Saat Ini');
        $sheet->setCellValue('D1', 'Tanggal In');
        $sheet->setCellValue('E1', 'PART NO');
        $sheet->setCellValue('F1', 'LOT NO');
        $sheet->setCellValue('G1', 'QTY');
        $sheet->setCellValue('H1', 'tag');
        $sheet->setCellValue('I1', 'Tag Warna');

        $line = 2;
        $no = 1;
        foreach ($Datas as $data) {
            $sheet->setCellValue('A' . $line, $no++);
            $sheet->setCellValue('B' . $line, $data->cur);
            $sheet->setCellValue('C' . $line, $data->nama_line);
            $sheet->setCellValue('D' . $line, $data->tgl_in);
            $sheet->setCellValue('E' . $line, $data->part_no);
            $sheet->setCellValue('F' . $line, $data->lot_no);
            $sheet->setCellValue('G' . $line, $data->qty_in);
            $sheet->setCellValue('H' . $line, $data->tag);
            $sheet->setCellValue('I' . $line, $data->warna_tag);
            $line++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Detail_Shikakari.xlsx';
        $writer->save(public_path('storage/excel/' . $filename));
        return ['file' => url('/') . '/storage/excel/' . $filename];
    }

    public function upd_next_process (Request $request){
        // dd($request->all());

        $id = $request->unp_id;
        $line = $request->unp_shikakari;

        // Cek apakah kode_line adalah numeric
        if (is_numeric($line)) {
            $cekDouble = DB::table('tb_next_process')
                ->where('part_no', $request->f_partno)
                ->where('lot_no', $request->f_lotno)
                ->where('kode_line', $line)
                ->where('keterangan', $line)
                ->count();

            if ($cekDouble > 0) {
                return [
                    'success' => false,
                    'message' => 'Invalid data. Shikakari sudah ada.',
                ];
            } else {
                $upd = DB::table('tb_next_process')
                    ->where('id_next_process', $id)
                    ->update(['kode_line' => $line, 'keterangan' => $line]);

                return [
                    'success' => true,
                    'message' => 'Data updated successfully.',
                ];
            }
        } else {
            // Jika bukan numeric, berikan respon kesalahan
            return [
                'success' => false,
                'message' => 'Invalid data. Update failed.',
            ];
        }
    }

    
}
