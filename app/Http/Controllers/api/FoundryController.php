<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\PouringModel;
use App\Models\ElastisitasModel;
use App\Models\SleeveModel;
use App\Models\KomposisiModel;

class FoundryController extends Controller
{
    public function getNikCasting (Request $request)
    {
        // dd($request->all());
        // kode section casting = 4
        return [
            "success"=>true,
            "datas" => DB::table('tb_karyawan')->select('nik','nama')->where('status_karyawan','!=','Off')->where('kode_section','=',4)->get(),
        ];
    }

    public function getNoCast (Request $request)
    {
        return [
            "success" => true,
            "datas" => DB::select("SELECT a.cast_no, c.cast_no as castnull FROM 
            (select cast_no, lot_no, part_no from tb_hasil_pouring)a 
            left join
            (select lot_no, part_no from tb_workresult where customer not in ('NPR','SINGAPORE'))b 
            on a.lot_no = b.lot_no and a.part_no = b.part_no
            LEFT JOIN 
            (select cast_no from tb_komposisi)c on a.cast_no = c.cast_no
            WHERE c.cast_no is NULL 
            group by a.cast_no, c.cast_no"),
        ];
    }

    public function getBarcodePouring (Request $request)
    {
        // dd($request->all());
        $inpbar = $request->input('barcode_no');
        $datas = DB::table('tb_workresult')
            ->select('part_no', 'lot_no')
            ->where('barcode_no', $inpbar)
            ->get();

        if (empty($datas[0]->lot_no)) {
            return [
                'message' => 'tidak ada record...',
                'success' => false,
            ];
        } else {
            return [
                'success' => true,
                'datas' => $datas,
            ];
        }
    }

    public function insPouring (Request $request)
    {
        // dd($request->all());
        $lotNo = $request->fehp_lot;
        $cekLot = PouringModel::where('lot_no',$lotNo)->count();
        
        if($cekLot >= 1){
            return [
                'message' => 'Lot No sudah ada .',
                'success' => false,
            ];
        } else {
            $upd = PouringModel::create([
                'id_hasil_pouring' => str::uuid(),
                'tgl_proses' => date('Y-m-d'),
                'barcode_no' => $request->fehp_sb,
                'part_no' => $request->fehp_part,
                'lot_no' => $request->fehp_lot,
                'cast_no' => $request->fehp_nocas,
                'leadle_no' => $request->fehp_leadle,
                'mesin_no' => $request->fehp_mesin,
                'yama' => $request->fehp_yama,
                'defect' => $request->fehp_defect,
                'keterangan' => $request->fehp_ket,
                'user' => $request->user()->name,
                'nik' => $request->select_nik_casting,
                'opr_name' => $request->nama_operator,
                'keterangan_1' => $request->fehp_ket1,
            ]);
    
            if ($upd) {
                return [
                    'message' => 'Update Berhasil !',
                    'success' => true,
                ];
            } else {
                return [
                    'message' => 'Gagal Update !',
                    'success' => false,
                ];
            }
        }
    }

    public function listPouring (Request $request)
    {
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;

        $Datas = DB::select(
            "SELECT a.*, (b.barcode_no)as bbar from 
        (select * from tb_hasil_pouring where tgl_proses between ? and ?)a
        left join
        (select * from tb_elastisitas )b on a.barcode_no = b.barcode_no       
        where (a.part_no like '%$search%' or a.lot_no like '%$search%' or a.cast_no like '%$search%' or a.barcode_no like '%$search%' or a.keterangan_1 like '%$search%')
        ORDER by a.tgl_proses ASC , a.cast_no ASC OFFSET " .
                $start .
                ' ROWS FETCH NEXT ' .
                $length .
                ' ROWS ONLY',[$tgl_awal, $tgl_akhir]
        );
        // dd($Datas);

        $co = DB::select("SELECT a.*, (b.barcode_no)as bbar from 
        (select * from tb_hasil_pouring where tgl_proses between ? and ?)a
        left join
        (select * from tb_elastisitas )b on a.barcode_no = b.barcode_no       
        where (a.part_no like '%$search%' or a.lot_no like '%$search%' or a.cast_no like '%$search%' or a.barcode_no like '%$search%' or a.keterangan_1 like '%$search%')",[$tgl_awal, $tgl_akhir]);

        $count = count($co);

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $Datas,
        ];
    }

    public function insMikrostruktur (Request $request)
    {
        // dd($request->all());

        $cek_datas = ElastisitasModel::where(
            'lot_no',
            $request->im_lot_no
        )->count();

        if ($cek_datas > 0) {
            return [
                'message' => 'Data sudah ada .',
                'success' => false,
            ];
        } else {
            $ins = ElastisitasModel::create([
                'id_elastisitas' => str::uuid(),
                'tgl_proses' => $request->im_tgl_proses,
                'part_no' => $request->im_part_no,
                'lot_no' => $request->im_lot_no,
                'cast_no' => $request->im_cast_no,
                'leadle_no' => $request->im_leadle_no,
                'mikrostruktur' => $request->im_mikrostruktur,
                'ketetapan' => $request->im_ketetapan,
                'keterangan' => $request->im_keterangan,
                'barcode_no' => $request->im_scan_barcode,
                'pemeriksa' => $request->im_opr,
                'ringkasan' => $request->im_ringkasan,
            ]);
    
            if ($ins) {
                return [
                    'message' => 'Insert Mikrostruktur Berhasil .',
                    'success' => true,
                ];
            } else {
                return [
                    'message' => 'Insert Mikrostruktur Gagal .',
                    'success' => false,
                ];
            }
        }
    }

    public function edtPouring (Request $request)
    {
        // dd($request->all());
        $cek_next_proses = DB::table('tb_hasil_produksi')
            ->where('lot_no', $request->ed_lotno)
            ->first();

        if ($cek_next_proses) {
            return [
                'message' => 'Failed, Next Process already completed. ',
                'success' => false,
            ];
        } else {
            $update_pouring = PouringModel::where(
                'id_hasil_pouring',
                $request->ed_id_pouring
            )->update([
                'cast_no' => $request->ed_castno,
                'leadle_no' => $request->ed_leadleno,
                'mesin_no' => $request->ed_mesinno,
                'yama' => $request->ed_yama,
                'defect' => $request->ed_defect,
                'keterangan' => $request->ed_keterangan,
                'keterangan_1' => $request->ed_keterangan1,
            ]);

            if ($update_pouring) {
                return [
                    'message' => 'Edit Success !',
                    'success' => true,
                ];
            } else {
                return [
                    'message' => 'Edit Gagal .',
                    'success' => false,
                ];
            }
        }
    }

    public function delHasilPouring ($id, $lot)
    {
        // dd($id, $lot);

        $cek_next_proses = DB::table('tb_hasil_produksi')
            ->where('lot_no', $lot)
            ->first();

        if ($cek_next_proses) {
            return [
                'message' => 'Failed, Next Process already completed. ',
                'success' => false,
            ];
        } else {
            $delPouring = PouringModel::where(
                'id_hasil_pouring',
                $id
            )->delete();

            if ($delPouring) {
                return [
                    'message' => 'Deleted Success !',
                    'success' => true,
                ];
            } else {
                return [
                    'message' => 'Deleted Gagal .',
                    'success' => false,
                ];
            }
        }
    }

    public function xlsPouring (Request $request)
    {
        // dd($request->all());
        $tglAwal = $request->tgl_awal;
        $tglAkhir = $request->tgl_akhir;

        $datas = DB::table('tb_hasil_pouring')
            ->select(
                'barcode_no',
                'part_no',
                'lot_no',
                'cast_no',
                'leadle_no',
                'mesin_no',
                'yama',
                'defect',
                'keterangan',
                'tgl_proses'
            )
            ->whereBetween('tgl_proses', [$tglAwal, $tglAkhir])
            ->get();

        if (count($datas) > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'NO');
            $sheet->setCellValue('B1', 'BARCODE NO');
            $sheet->setCellValue('C1', 'TGL PROSES');
            $sheet->setCellValue('D1', 'PART NO');
            $sheet->setCellValue('E1', 'LOT NO');
            $sheet->setCellValue('F1', 'CASTING NO');
            $sheet->setCellValue('G1', 'LEADLE NO');
            $sheet->setCellValue('H1', 'MESIN NO');
            $sheet->setCellValue('I1', 'YAMA');
            $sheet->setCellValue('J1', 'DEFECT');
            $sheet->setCellValue('K1', 'KETERANGAN');

            $line = 2;
            $no = 1;
            foreach ($datas as $data) {
                $sheet->setCellValue('A' . $line, $no++);
                $sheet->setCellValue('B' . $line, $data->barcode_no);
                $sheet->setCellValue('C' . $line, $data->tgl_proses);
                $sheet->setCellValue('D' . $line, $data->part_no);
                $sheet->setCellValue('E' . $line, $data->lot_no);
                $sheet->setCellValue('F' . $line, $data->cast_no);
                $sheet->setCellValue('G' . $line, $data->leadle_no);
                $sheet->setCellValue('H' . $line, $data->mesin_no);
                $sheet->setCellValue('I' . $line, $data->yama);
                $sheet->setCellValue('J' . $line, $data->defect);
                $sheet->setCellValue('K' . $line, $data->keterangan);
                $line++;
            }

            $writer = new Xlsx($spreadsheet);
            $filename = 'Hasil_Pouring.xlsx';
            $writer->save(public_path('storage/excel/' . $filename));
            return [
                'file' => url('/') . '/storage/excel/' . $filename,
                'message' => 'Berhasil Export Data .',
                'success' => true,
            ];
        } else {
            return ['message' => 'No Data', 'success' => false];
        }
    }

    public function listElastisitas (Request $request)
    {
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;

        $datas = DB::table('tb_elastisitas as a')
            ->leftJoin('tb_master_mohan as b', 'a.part_no', '=', 'b.part_no')
            ->select('a.*', 'b.m_camu')
            ->whereBetween('tgl_proses',[$tgl_awal, $tgl_akhir])
            ->where(function ($q) use ($search) {
                $q
                    ->where('a.part_no', 'like', '%' . $search . '%')
                    ->orwhere('a.lot_no', 'like', '%' . $search . '%')
                    ->orwhere('a.barcode_no', 'like', '%' . $search . '%')
                    ->orwhere('a.cast_no', 'like', '%' . $search . '%');
            })
            ->orderBy('a.tgl_proses', 'desc', 'a.cast_no', 'asc')
            ->skip($start)
            ->take($length)
            ->get();

        $count = DB::table('tb_elastisitas as a')
            ->leftJoin('tb_master_mohan as b', 'a.part_no', '=', 'b.part_no')
            ->select('a.*', 'b.m_camu')
            ->whereBetween('tgl_proses',[$tgl_awal, $tgl_akhir])
            ->where(function ($q) use ($search) {
                $q
                    ->where('a.part_no', 'like', '%' . $search . '%')
                    ->orwhere('a.lot_no', 'like', '%' . $search . '%')
                    ->orwhere('a.barcode_no', 'like', '%' . $search . '%')
                    ->orwhere('a.cast_no', 'like', '%' . $search . '%');
            })
            ->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $datas,
        ];
    }

    public function insMohan (Request $request)
    {
        // dd($request->all());
        $upd_um = DB::table('tb_master_mohan')->insert([
            'id_master_mohan' => str::uuid(),
            'part_no' => $request->um_part_no,
            'm_camu' => $request->um_camu,
        ]);

        if ($upd_um) {
            return [
                'message' => 'Insert Data Mohan berhasil .',
                'success' => true,
            ];
        } else {
            return [
                'message' => 'Insert gagal .',
                'success' => false,
            ];
        }
    }

    public function updHardness (Request $request)
    {
        // dd($request->all());

        $upd_uh = ElastisitasModel::where(
            'id_elastisitas',
            $request->uh_id
        )->update([
            'hard_1' => $request->uh_h1,
            'hard_2' => $request->uh_h2,
            'hard_3' => $request->uh_h3,
            'hard_4' => $request->uh_h4,
        ]);

        if ($upd_uh) {
            return [
                'message' => 'Update Hardness berhasil .',
                'success' => true,
            ];
        } else {
            return [
                'message' => 'Update Hardness gagal .',
                'success' => false,
            ];
        }
    }

    public function edtHardness (Request $request)
    {
        // dd($request->all());
        $upd_eh = ElastisitasModel::where(
            'id_elastisitas',
            $request->eh_id
        )->update([
            'hard_1' => $request->eh_h1,
            'hard_2' => $request->eh_h2,
            'hard_3' => $request->eh_h3,
            'hard_4' => $request->eh_h4,
        ]);

        if ($upd_eh) {
            return [
                'message' => 'Update Hardness berhasil .',
                'success' => true,
            ];
        } else {
            return [
                'message' => 'Update Hardness gagal .',
                'success' => false,
            ];
        }
    }

    public function updElastisitas (Request $request)
    {
        // dd($request->all());
        $upd_ue = ElastisitasModel::where(
            'id_elastisitas',
            $request->ue_id
        )->update([
            'diameter' => $request->ue_diameter,
            'b1' => $request->ue_b1,
            'b2' => $request->ue_b2,
            'b3' => $request->ue_b3,
            'b4' => $request->ue_b4,
            'b5' => $request->ue_b5,
            't1' => $request->ue_t1,
            't2' => $request->ue_t2,
            't3' => $request->ue_t3,
            't4' => $request->ue_t4,
            't5' => $request->ue_t5,
            'w1' => $request->ue_w1,
            'w2' => $request->ue_w2,
            'w3' => $request->ue_w3,
            'w4' => $request->ue_w4,
            'w5' => $request->ue_w5,
            'e1' => $request->ue_e1,
            'e2' => $request->ue_e2,
            'e3' => $request->ue_e3,
            'e4' => $request->ue_e4,
            'e5' => $request->ue_e5,
            's' => $request->ue_s,
            'ka_1' => $request->ue_kak1,
            'ka_2' => $request->ue_kak2,
            'avg_e' => $request->ue_avg,
        ]);

        if ($upd_ue) {
            return [
                'message' => 'Update Elastisitas berhasil .',
                'success' => true,
            ];
        } else {
            return [
                'message' => 'Update Elastisitas gagal .',
                'success' => false,
            ];
        }
    }

    public function edtElastisitas (Request $request)
    {
        // dd($request->all());
        $upd_ee = ElastisitasModel::where(
            'id_elastisitas',
            $request->ee_id
        )->update([
            'diameter' => $request->ee_diameter,
            'b1' => $request->ee_b1,
            'b2' => $request->ee_b2,
            'b3' => $request->ee_b3,
            'b4' => $request->ee_b4,
            'b5' => $request->ee_b5,
            't1' => $request->ee_t1,
            't2' => $request->ee_t2,
            't3' => $request->ee_t3,
            't4' => $request->ee_t4,
            't5' => $request->ee_t5,
            'w1' => $request->ee_w1,
            'w2' => $request->ee_w2,
            'w3' => $request->ee_w3,
            'w4' => $request->ee_w4,
            'w5' => $request->ee_w5,
            'e1' => $request->ee_e1,
            'e2' => $request->ee_e2,
            'e3' => $request->ee_e3,
            'e4' => $request->ee_e4,
            'e5' => $request->ee_e5,
            's' => $request->ee_s,
            'ka_1' => $request->ee_kak1,
            'ka_2' => $request->ee_kak2,
            'avg_e' => $request->ee_avg,
        ]);

        if ($upd_ee) {
            return [
                'message' => 'Rubah data Elastisitas berhasil .',
                'success' => true,
            ];
        } else {
            return [
                'message' => 'Rubah data Elastisitas gagal .',
                'success' => false,
            ];
        }
    }

    public function delElastisitas ($id)
    {
        // dd($id);
        $del_el = DB::table('tb_elastisitas')
                ->where('id_elastisitas', $id)
                ->delete();

            if ($del_el) {
                return [
                    'message' => 'Hapus data Elastisitas berhasil .',
                    'success' => true,
                ];
            } else {
                return [
                    'message' => 'Hapus data Elastisitas gagal .',
                    'success' => false,
                ];
            }
    }

    public function listPermintaanSleeve (Request $request)
    {
        // dd($request->all());

        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $awal = $request->input('tgl_awal');
        $akhir = $request->input('tgl_akhir');
        $status_permintaan = $request->input('status_permintaan');

        $allStatus = ['Open', 'Proses', 'Close'];
        $liststatus = [];
        if ($status_permintaan == 'All') {
            $liststatus = array_merge($liststatus, $allStatus);
        } else {
            $liststatus[] = $status_permintaan;
        }

        $query = DB::table('tb_permintaan_sleeve')
        ->whereIn('status_ps', $liststatus)
        ->where(function ($q) use ($search) {
            $q->where('tgl_request', 'like', '%' . $search . '%')
              ->orWhere('barcode_no', 'like', '%' . $search . '%')
              ->orWhere('item', 'like', '%' . $search . '%');
        });

        // Jika status tidak "Open", tambahkan filter tanggal
        if ($status_permintaan !== 'Open') {
            $query->whereBetween('tgl_request', [$awal, $akhir]);
        }

        $datas = $query->orderBy('created_at', 'desc')
        ->skip($start)
        ->take($length)
        ->get();

        
        $countQuery = DB::table('tb_permintaan_sleeve')
        ->whereIn('status_ps',$liststatus)
        ->where(function ($q) use ($search) {
            $q
            ->where('tgl_request', 'like', '%' . $search . '%')
            ->orwhere('barcode_no', 'like', '%' . $search . '%')
            ->orwhere('item', 'like', '%' . $search . '%');
        });

        if ($status_permintaan !== 'Open') {
            $countQuery->whereBetween('tgl_request', [$awal, $akhir]);
        }
        
        $count = $countQuery->count();
        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $datas,
        ];
    }

    public function prosesPermintaanSleeve (Request $request)
    {
        // dd($request->all());
        $upd_req_sleeve_pro = SleeveModel::where(
            'id_permintaan_sleeve',
            $request->id
        )->update([
            'status_ps' => 'Proses',
        ]);

        if ($upd_req_sleeve_pro) {
            return [
                'message' => 'Update Proses Permintaan Sleeve berhasil .',
                'success' => true,
            ];
        } else {
            return [
                'message' => 'Update gagal .',
                'success' => false,
            ];
        }
    }

    public function insKomposisi (Request $request)
    {
        // dd($request->all());

        $section = $request->user()->departments->pluck('section')->toArray();
        $fil = $request->fek_fil;
        
        if(in_array('Admin', $section) || in_array('CASTING', $section)) {
            if($fil == 'add'){
                $ins = KomposisiModel::create([
                    'id_komposisi' => str::uuid(),
                    'opr_qc' => $request->select_nik_casting,
                    'opr_melting' => $request->select_nik_casting2,
                    'tgl_cek' => $request->fek_tglCek,
                    'cast_no' => $request->fek_castNo,
                    'c' => $request->fek_c,
                    'si' => $request->fek_si,
                    'mn' => $request->fek_mn,
                    'p' => $request->fek_p,
                    's' => $request->fek_s,
                    'b' => $request->fek_b,
                    'cu' => $request->fek_cu,
                    'sn' => $request->fek_sn,
                    'ni' => $request->fek_ni,
                    'cr' => $request->fek_cr,
                ]);
    
                if ($ins){
                    return [
                        'message' => 'Insert Data Berhasil .',
                        'success' => true,
                    ];
                } else {
                    return [
                        'message' => 'Insert Data Gagal .',
                        'success' => false,
                    ];
                }
            } else {
                $upd = KomposisiModel::where('id_komposisi',$request->fek_id)->update([
                    'opr_qc' => $request->select_nik_casting,
                    'opr_melting' => $request->select_nik_casting2,
                    'tgl_cek' => $request->fek_tglCek,
                    // 'cast_no' => $request->fek_castNo,
                    'c' => $request->fek_c,
                    'si' => $request->fek_si,
                    'mn' => $request->fek_mn,
                    'p' => $request->fek_p,
                    's' => $request->fek_s,
                    'b' => $request->fek_b,
                    'cu' => $request->fek_cu,
                    'sn' => $request->fek_sn,
                    'ni' => $request->fek_ni,
                    'cr' => $request->fek_cr,
                ]);
    
                if ($upd){
                    return [
                        'message' => 'Update Data Berhasil .',
                        'success' => true,
                    ];
                } else {
                    return [
                        'message' => 'Update Data Gagal .',
                        'success' => false,
                    ];
                }
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Access Denied !']);
        }
    }

    public function listKomposisi (Request $request)
    {
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        
        $awal = $request->input('tgl_awal');
        $akhir = $request->input('tgl_akhir');
        $lot_no = $request->lotNo;; // Nilai input yang akan dicari

        if ($lot_no == null){
            $datas = DB::table('tb_komposisi')
            ->whereBetween('tgl_cek',[$awal, $akhir])
            ->where(function ($q) use ($search) {
                $q
                    ->where('cast_no', 'like', '%' . $search . '%')
                    ->orwhere('tgl_cek', 'like', '%' . $search . '%');
            })
            ->skip($start)
            ->take($length)
            ->get();
    
            $count = DB::table('tb_komposisi')
            ->whereBetween('tgl_cek',[$awal, $akhir])
            ->where(function ($q) use ($search) {
                $q
                    ->where('cast_no', 'like', '%' . $search . '%')
                    ->orwhere('tgl_cek', 'like', '%' . $search . '%');
            })
            ->count();

        } else {
            $datas = DB::table('tb_hasil_pouring as a')
            ->leftJoin('tb_komposisi as b', 'a.cast_no', '=', 'b.cast_no')
            ->select('a.lot_no','b.id_komposisi','b.opr_qc','b.opr_melting','b.tgl_cek','b.cast_no','b.c','b.si','b.mn','b.p','b.s','b.b','b.cu','b.sn','b.ni','b.cr')
            ->where('a.lot_no', $lot_no)
            ->whereNotNull('b.cast_no')
            ->get();
    
            $count = DB::table('tb_hasil_pouring as a')
            ->leftJoin('tb_komposisi as b', 'a.cast_no', '=', 'b.cast_no')
            ->select('a.lot_no','b.id_komposisi','b.opr_qc','b.opr_melting','b.tgl_cek','b.cast_no','b.c','b.si','b.mn','b.p','b.s','b.b','b.cu','b.sn','b.ni','b.cr')
            ->where('a.lot_no', $lot_no)
            ->whereNotNull('b.cast_no')
            ->count();
        }


        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $datas,
        ];
    }

    public function delKomposisi ($id, Request $request)
    {

        if($request->user()->hasDepartment('CASTING') === True){
            $del = KomposisiModel::where('id_komposisi',$id)->delete();
            if($del){
                return [
                    'message' => 'Delete data Komposisi Berhasil .',
                    'success' => true,
                ];
            } else {
                return [
                    'message' => 'Delete data gagal .',
                    'success' => false,
                ];
            }
        } else {
            return [
                'message' => 'Access Denied .',
                'success' => false,
            ];
        }
    }
    
}
