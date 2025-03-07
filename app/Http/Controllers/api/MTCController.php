<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Events\EventMessage;
use App\Events\EventPPIC;
use App\Models\PermintaanPerbaikanModel;
use App\Models\MesinModel;
use App\Models\MTCSchMesinModel;
use App\Models\MTCReportPekerjaanModel;

class MTCController extends Controller
{
    public function grafikjam($period){
        
        $now = Date('Y-m');
        $thn = substr($period, 0, 4);
        $bln = substr($period, 5, 2);

        if ($period == $now) {
            $totjam = DB::connection('sqlsrv')->select('select departemen, sum(jam_rusak) as jam, sum(jam_menunggu) as menunggu from v_jam_kerusakan group by departemen');
        } else {
            $totjam = DB::connection('sqlsrv')->select('select departemen, sum(jam_rusak) as jam, sum(jam_menunggu) as menunggu from tb_jamkerusakan where DATEPART(YEAR,tgl_rekap) = ? and DATEPART(MONTH,tgl_rekap)= ? group by departemen',[$thn,$bln]);
        }

        return $totjam;
    }

    public function detailjam(Request $request){
        $peri = $request->input('period');
        $dept = $request->input('dept');
        $now = Date('Y-m');
        $thn = substr($peri, 0, 4);
        $bln = substr($peri, 5, 2);
        if ($peri == $now) {
            $detail = DB::connection('sqlsrv')->select(
                    "select top 5 no_perbaikan, no_induk_mesin, no_urut_mesin, nama_mesin, jam_menunggu, jam_rusak from v_jam_kerusakan where departemen = ? order by jam_rusak desc",[$dept]);
        } else {
            $detail = DB::connection('sqlsrv')->select("select top 5 no_perbaikan, no_induk_mesin, nama_mesin, jam_menunggu, jam_rusak from tb_jamkerusakan where DATEPART(YEAR,tgl_rekap) = ? and DATEPART(MONTH,tgl_rekap)= ? and departemen = ? order by jam_rusak desc",[$thn,$bln,$dept]);
        }
        return $detail;
    }

    public function listPermintaanPerbaikan (Request $request){
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        
        $sectionArray = $request->user()->departments->pluck('section')->toArray(); // section
        $section = "'" . implode("','",$sectionArray) . "'";
        $awal = $request->input('tgl_awal');
        $akhir = $request->input('tgl_akhir');
        $status_permintaan = $request->input('status_permintaan');

        $allStatus = ['open', 'process', 'selesai', 'complete', 'pending', 'reject', 'TempReject'];
        $liststatus = [];
        if ($status_permintaan == 'All') {
            $liststatus = array_merge($liststatus, $allStatus);
        } else {
            $liststatus[] = $status_permintaan;
        }

        $status = "'" . implode("','",$liststatus) . "'";

        $datas = DB::connection('sqlsrv')->select(
            "SELECT p.id_perbaikan, tanggal_rusak, no_perbaikan, departemen, shift, nama_mesin, no_induk_mesin, masalah, kondisi, tindakan, tanggal_selesai,no_urut_mesin, status, s.tanggal_rencana_selesai, s.keterangan, operator = STUFF((SELECT N', ' + nama 
        FROM (select a.*, b.nama from tb_perbaikan a left join tb_operator_mtc b on a.id_perbaikan = b.id_perbaikan) AS p2 
         WHERE p2.id_perbaikan = p.id_perbaikan
         
         ORDER BY p.tanggal_rusak
         FOR XML PATH(N'')), 1, 2, N'')
        FROM (select a.*, b.nama from tb_perbaikan a left join tb_operator_mtc b on a.id_perbaikan = b.id_perbaikan where a.departemen in ($section)) AS p 
        left join 
        (select id_perbaikan, tanggal_rencana_selesai, keterangan from tb_schedule)s ON p.id_perbaikan = s.id_perbaikan where p.status in ($status)
        group by p.id_perbaikan, tanggal_rusak, no_perbaikan, departemen, shift, nama_mesin, no_induk_mesin, masalah, kondisi, operator, tindakan, tanggal_selesai, no_urut_mesin, status, s.tanggal_rencana_selesai, s.keterangan
        ORDER BY tanggal_rusak OFFSET " .
                $start .
                ' ROWS FETCH NEXT ' .
                $length .
                ' ROWS ONLY'
        );

        $count = DB::connection('sqlsrv')
            ->select("select coalesce(count(*),0) as total from (SELECT p.id_perbaikan, tanggal_rusak, no_perbaikan, departemen, shift, nama_mesin, no_induk_mesin, masalah, kondisi, tindakan, tanggal_selesai,no_urut_mesin, status, s.tanggal_rencana_selesai, s.keterangan, operator = STUFF((SELECT N', ' + nama 
        FROM (select a.*, b.nama from tb_perbaikan a left join tb_operator_mtc b on a.id_perbaikan = b.id_perbaikan) AS p2 
         WHERE p2.id_perbaikan = p.id_perbaikan
         
         ORDER BY p.tanggal_rusak
         FOR XML PATH(N'')), 1, 2, N'')
        FROM (select a.*, b.nama from tb_perbaikan a left join tb_operator_mtc b on a.id_perbaikan = b.id_perbaikan where a.departemen in ($section)) AS p 
        left join 
        (select id_perbaikan, tanggal_rencana_selesai, keterangan from tb_schedule)s ON p.id_perbaikan = s.id_perbaikan
      where p .status in ($status)
      group by p.id_perbaikan, tanggal_rusak, no_perbaikan, departemen, shift, nama_mesin, no_induk_mesin, masalah, kondisi, operator, tindakan, no_urut_mesin, tanggal_selesai, status, s.tanggal_rencana_selesai, s.keterangan)a")[0]
            ->total;

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $datas,
        ];
    }

    public function notifPermintaanPerbaikan (Request $request){
        $section = $request->user()->departments->pluck('section')->toArray();

        $n = PermintaanPerbaikanModel::where('status','=','selesai')->whereIn('departemen',$section)->count();

        return [
            'notif_permintaan_perbaikan' => $n, 
            'success' => true
        ];
    }

    public function nomerPermintaanPerbaikan (Request $request)
    {
        // dd($request->all());
        $nomer = Carbon::now('Asia/Jakarta')->format('ymdHis');

        return (object) [
            'no_perbaikan' => 'PM'.$nomer,
        ];
    }

    public function nomerIndukMesin (Request $request)
    {
        // dd($request->all());
        $mesin = MesinModel::select('no_induk','nama_mesin','no_urut')->where('kondisi', '=', 'OK')->get();
        return  [
            'success' => true,
            'datas' => $mesin,
        ];
    }

    public function insPermintaanPerbaikanMesin (Request $request)
    {
        // dd($request->all());

        $request->validate([
            'no_permintaan' => 'required|min:12',
            'shift' => 'required',
            // 'nomer_induk_mesin' => 'required_if:r_non_mesin,null',
            'masalah' => 'required',
        ]);

        if($request->kategori == 'r_non_mesin'){
            $nomer_induk_mesin = 'NM';
            $nomer_mesin = 0;
        } else {
            $nomer_induk_mesin = $request->nomer_induk_mesin;
            $nomer_mesin = $request->no_mesin;
        }

        $id = Str::uuid();
        $user_id = $request->user()->id;
        $section = $request->user()->departments->pluck('section')->first();
        $tanggal = Carbon::now('Asia/Jakarta');

        if ($request['ppic'] == 'Y') {
            $klasi = 'C';
        } else {
            $klasi = null;
        }

        $insert = PermintaanPerbaikanModel::create([
            'id_perbaikan' => $id,
            'no_perbaikan' => $request->no_permintaan,
            'departemen' => $section,
            'shift' => $request['shift'],
            'tanggal_rusak' => $tanggal,
            'nama_mesin' => $request->nama_mesin,
            'no_induk_mesin' => $nomer_induk_mesin,
            'no_urut_mesin' => $nomer_mesin,
            'masalah' => $request['masalah'],
            'kondisi' => $request['kondisi'],
            'klasifikasi' => $klasi,
            'lapor_ppic' => $request['ppic'],
            'status' => 'open',
            'user_id' => $user_id,
        ]);

        if ($insert) {
            Session::flash('alert-success', 'Tambah Data berhasil !');

            //Notification::send($users, new RequestNotif($details));

            $mes = [
                'judul' => $section,
                'sub' => $request->nama_mesin,
                'isi' => $request['masalah'] . ' ' . $request['kondisi'],
                'ppic' => $request['ppic'],
            ];
            event(new EventMessage($mes));

            if ($request['ppic'] == 'Y') {
                event(new EventPPIC($mes));
                $isi =
                    "

                Dari : " .
                    $section .
                    ",
User : " .
                    $request->user()->name .
                    ",
Mesin : " .
                    $request->nama_mesin .
                    ",
No. : " .
                    $nomer_induk_mesin .
                    ' (' .
                    $nomer_mesin .
                    "),
Masalah : " .
                    $request['masalah'] .
                    ",
Kondisi : " .
                    $request['kondisi'] .
                    "
                ";
                // $this->send_togroup($isi);
            }

            return [
                'success' => true,
                'message' => 'Tambah Data berhasil !',
            ];
        } else {
            Session::flash('alert-danger', 'Tambah Data gagal !');
            return [
                'success' => false,
                'message' => 'Tambah Data Gagal !',
            ];
        }
    }

    public function aprPermintaanReject (Request $request)
    {
        // dd($request->all());

        $idPerbaikan = $request->idPerbaikan;
        $updPerbaikan = PermintaanPerbaikanModel::where(
            'id_perbaikan',
            $idPerbaikan
        )->update([
            'status' => 'reject',
        ]);

        if ($updPerbaikan) {
            return [
                'success' => true,
                'message' => 'Reject diterima .',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Reject gagal .',
            ];
        }
    }

    public function edtPermintaanPerbaikan (Request $request)
    {
        // dd($request->all());
        $id = $request->id;

        $upd = PermintaanPerbaikanModel::where('id_perbaikan',$id)->update([
            'kondisi' => $request->kondisi,
            'masalah' => $request->masalah,
        ]);

        if($upd){
            return [
                'success' => true,
                'message' => 'Update data berhasil .',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Update data gagal .',
            ];
        }
    }

    public function delPermintaanPerbaikan ($id)
    {
        // dd($id);
        $del = PermintaanPerbaikanModel::where('id_perbaikan',$id)->delete();

        if($del){
            return [
                'success' => true,
                'message' => 'Hapus permintaan perbaikan berhasil .',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Hapus permintaan perbaikan gagal .',
            ];
        }
    }

    public function updTerimaPerbaikan ($id)
    {
        // dd($id);
        $upd = PermintaanPerbaikanModel::where('id_perbaikan',$id)->update([
            'status' => 'complete',
        ]);

        if($upd){
            return [
                'success' => true,
                'message' => 'Terima perbaikan berhasil .',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Terima perbaikan gagal .',
            ];
        }
    }

    public function insSchedule (Request $request)
    {
        // dd($request->all());
        $noPermintaanPerbaikan = $this->nomerPermintaanPerbaikan($request);

        $section = $request->user()->departments->pluck('section')->first(); // section
        $user = auth()->user();

        $idSch = str::uuid();
        $idPerbaikan = str::uuid();
        $insSch = MTCSchMesinModel::create([
            'id_sch_mesin_tahunan' => $idSch,
            'id_perbaikan' => $idPerbaikan,
            'no_perbaikan' => $noPermintaanPerbaikan->no_perbaikan,
            'no_induk_mesin' => $request->nomer_induk_mesin,
            'nama_mesin' => $request->nama_mesin,
            'jadwal_perbaikan' => $request->s_schedule,
            'status_sch' => 'open',
            'jenis_perbaikan' => $request->fil,
        ]);

        if ($insSch) {
            $tanggal = Carbon::now();
            $insPerbaikan = PermintaanPerbaikanModel::create([
                'id_perbaikan' => $idPerbaikan,
                'no_perbaikan' => $noPermintaanPerbaikan->no_perbaikan,
                'departemen' => $section,
                'shift' => 'Non Shift',
                'tanggal_rusak' => $tanggal,
                'nama_mesin' => $request->nama_mesin,
                'no_induk_mesin' => $request->nomer_induk_mesin,
                'no_urut_mesin' => $request->no_mesin,
                'masalah' => $request->s_masalah,
                'kondisi' => $request->s_kondisi,
                'user_id' => $user->id,
                'status' => 'open',
            ]);

            return [
                'message' => 'Schedule berhasil disimpan .',
                'success' => true,
            ];
        } else {
            return [
                'message' => 'Schedule gagal disimpan .',
                'success' => false,
            ];
        }
    }

    public function listSchPemeriksaan (Request $request)
    {
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        // dd($tgl, $shift);
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $status_sch = $request->status;
        $jenis = $request->jenis;
        // dd($status_sch);
        $Datas = DB::table('tb_sch_mesin_tahunan as a')
            ->leftJoin(
                'tb_perbaikan as b',
                'a.id_perbaikan',
                '=',
                'b.id_perbaikan'
            )
            ->select('a.*', 'b.status', 'b.tanggal_selesai', 'no_urut_mesin')
            ->where('jenis_perbaikan',$jenis)
            ->whereBetween('a.jadwal_perbaikan', [$tgl_awal, $tgl_akhir])
            ->where(function ($q) use ($search) {
                $q
                    ->where('a.nama_mesin', 'like', '%' . $search . '%')
                    ->orWhere('a.no_induk_mesin', 'like', '%' . $search . '%');
            })
            ->orderBy('a.jadwal_perbaikan', 'asc')
            ->skip($start)
            ->take($length);

        // Check if 'All' is selected
        if ($status_sch != 'All') {
            $Datas->where('b.status', $status_sch);
        }

        $Datas = $Datas->get();

        $count = DB::table('tb_sch_mesin_tahunan as a')
            ->leftJoin(
                'tb_perbaikan as b',
                'a.id_perbaikan',
                '=',
                'b.id_perbaikan'
            )
            ->select('a.*', 'b.status', 'b.tanggal_selesai', 'no_urut_mesin')
            ->where('jenis_perbaikan',$jenis)
            ->whereBetween('a.jadwal_perbaikan', [$tgl_awal, $tgl_akhir])
            ->where(function ($q) use ($search) {
                $q
                    ->where('a.nama_mesin', 'like', '%' . $search . '%')
                    ->orWhere('a.no_induk_mesin', 'like', '%' . $search . '%');
            });

        if ($status_sch != 'All') {
            $count->where('b.status', $status_sch);
        }

        $count = $count->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $Datas,
        ];
    }

    public function uplLampiran (Request $request)
    {
        // dd($request->all());

        $request->validate([
            'file_ud_upload' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('file_ud_upload')) {

            $file_name = $request
                ->file('file_ud_upload')
                ->getClientOriginalName();
            $request
                ->file('file_ud_upload')
                ->move(public_path('storage/file/mesin_tahunan/'), $file_name);
            $updLampiran = MTCSchMesinModel::where(
                'id_sch_mesin_tahunan',
                $request->ud_id
            )->update([
                'lampiran' => $file_name,
            ]);

            if ($updLampiran) {
                if (
                    $request->ud_status == 'selesai' ||
                    $request->ud_status == 'complete'
                ) {
                    PermintaanPerbaikanModel::where(
                        'id_perbaikan',
                        $request->ud_idPerbaikan
                    )->update([
                        'status' => 'complete',
                    ]);
                } else {
                    return [
                        'message' => 'Update Status gagal .',
                        'success' => false,
                    ];
                }
            }

            return [
                'message' => 'Upload Lampiran berhasil .',
                'success' => true,
            ];
        } else {
            return [
                'message' => 'Upload Lampiran gagal .',
                'success' => false,
            ];
        }
    }

    public function aprHasilPekerjaan (Request $request)
    {
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $tgl1 = $request->tgl_awal;
        $tgl2 = $request->tgl_akhir;
        $shift = $request->shift;

        $selShift = MTCReportPekerjaanModel::select('shift')
            ->groupBy('shift')
            ->get();

        $arrShift = [];
        if ($shift == null) {
            foreach ($selShift as $key) {
                array_push($arrShift, $key->shift);
            }
        } else {
            array_push($arrShift, $shift);
        }

        $Datas = DB::table('tb_karyawan as a')
            ->leftJoin('tb_mtc_report_pekerjaan as b', 'b.nik', '=', 'a.NIK')
            ->select(
                'b.id_report_pekerjaan',
                'b.tgl_pekerjaan',
                'b.shift',
                'b.status_pekerjaan',
                'b.approve',
                'b.tgl_approve',
                'b.deskripsi',
                'a.nama',
                'b.deskripsi_2'
            )
            ->whereBetween('b.tgl_pekerjaan', [$tgl1, $tgl2])
            ->whereIn('b.shift', $arrShift)
            ->where(function ($q) use ($search) {
                $q
                    ->where('b.tgl_pekerjaan', 'like', '%' . $search . '%')
                    ->orWhere('b.shift', 'like', '%' . $search . '%');
            })
            ->orderBy('b.tgl_pekerjaan', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $count = DB::table('tb_karyawan as a')
        ->leftJoin('tb_mtc_report_pekerjaan as b', 'b.nik', '=', 'a.NIK')
            ->select(
                'b.id_report_pekerjaan',
                'b.tgl_pekerjaan',
                'b.shift',
                'b.status_pekerjaan',
                'b.approve',
                'b.tgl_approve',
                'b.deskripsi',
                'a.nama',
                'b.deskripsi_2'
            )
            ->whereBetween('b.tgl_pekerjaan', [$tgl1, $tgl2])
            ->whereIn('b.shift', $arrShift)
            ->where(function ($q) use ($search) {
                $q
                    ->where('b.tgl_pekerjaan', 'like', '%' . $search . '%')
                    ->orWhere('b.shift', 'like', '%' . $search . '%');
            })
            ->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $Datas,
        ];
    }

    public function listHasilPerbaikan (Request $request)
    {
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $tgl = $request->tgl;
        $shift = $request->shift;
        // dd($tgl, $shift);

        $Datas = PermintaanPerbaikanModel::select(
            'shift_selesai',
            'nama_mesin',
            'no_urut_mesin',
            'masalah',
            'tindakan',
            'tanggal_selesai',
            'total_jam_perbaikan'
        )
            ->where(DB::raw('CONVERT(VARCHAR, tanggal_selesai, 23)'), $tgl)
            ->where('shift_selesai', $shift)
            ->whereIn('status', ['complete', 'selesai'])
            ->where(function ($q) use ($search) {
                $q
                    ->where('nama_mesin', 'like', '%' . $search . '%')
                    ->orWhere('masalah', 'like', '%' . $search . '%');
            })
            ->skip($start)
            ->take($length)
            ->get();
        // dd($Datas);

        $count = PermintaanPerbaikanModel::select(
            'shift_selesai',
            'nama_mesin',
            'no_urut_mesin',
            'masalah',
            'tindakan',
            'tanggal_selesai',
            'total_jam_perbaikan'
        )
            ->where(DB::raw('CONVERT(VARCHAR, tanggal_selesai, 23)'), $tgl)
            ->where('shift_selesai', $shift)
            ->whereIn('status', ['complete', 'selesai'])
            ->where(function ($q) use ($search) {
                $q
                    ->where('nama_mesin', 'like', '%' . $search . '%')
                    ->orWhere('masalah', 'like', '%' . $search . '%');
            })
            ->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $Datas,
        ];
    }

    public function updHasilPekerjaan (Request $request)
    {
        $statusApprove = $request->dtlStatusPekerjaan;

        if ($statusApprove == 'Open') {
            $updApprove = MTCReportPekerjaanModel::where(
                'id_report_pekerjaan',
                $request->dtlId
            )->update([
                'approve' => $request->user()->name,
                'tgl_approve' => date('Y-m-d'),
                'status_pekerjaan' => 'Approve',
            ]);

            if ($updApprove) {
                return [
                    'success' => true,
                    'message' => 'Approve pekerjaan berhasil .',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Approve pekerjaan gagal .',
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Approve pekerjaan sudah dilakukan .',
            ];
        }
    }

}
