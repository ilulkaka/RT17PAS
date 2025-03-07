<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\ReqJiguModel;
use App\Models\JiguSelesaiModel;
use Carbon\Carbon;

class TECHController extends Controller
{
    public function notif_req_jigu (Request $request)
    {
        $section = $request->user()->departments->pluck('section')->toArray();

        $n = ReqJiguModel::where('status','=','Waiting User')->whereIn('dept',$section)->count();

        return 
        ['notif_reqJigu' => $n, 'success' => true];
    }

    public function get_namaMesin (){
        $datas = DB::select("select nama_mesin from tb_mesin where kondisi = 'OK' group by nama_mesin");

        return [
            'success' => true,
            'datas'=> $datas];
    }

    public function inp_req_jigu (Request $request){
        // dd($request->all());

        $section = $request->user()->departments->pluck('section')->first();

        $date = now();
        $nolap = $request->input('no_laporan');
        $cek = ReqJiguModel::where('no_laporan', $nolap)->count();

        if ($cek > 0) {
            Session::flash('alert-danger', 'No Laporan Sudah ada !');
            return redirect()->route('frm_repair_jigu');
        } else {
            $in = ReqJiguModel::create([
                'id_permintaan' => Str::uuid(),
                'tanggal_permintaan' => $date,
                'no_laporan' => $request->no_laporan,
                'dept' => $section,
                'nama_user' => $request->user()->name,
                'tujuan' => $request->tujuan,
                'permintaan' => $request->unik,
                'jenis_item' => $request->jenis_item,
                'nama_mesin' => $request->nama_mesin,
                'nama_item' => $request->nama_item,
                'material' => $request->material,
                'ukuran' => $request->ukuran,
                'qty' => $request->qty,
                'satuan' => $request->satuan,
                'alasan' => $request->alasan,
                'permintaan_perbaikan' => $request->permintaan_perbaikan,
                'status' => 'Open',
                'nouki' => $request->nouki,
            ]);
        }

        if ($in) {
            Session::flash('success', 'Tambah Data berhasil !');
        } else {
            Session::flash('alert-danger', 'Tambah Data gagal !');
        }

        return redirect()->route('frm_repair_jigu');

    }

    public function no_req_jigu (Request $request){
        $nomer = DB::select('exec no_technical ?,?', [
            $request['tanggal_permintaan'],
            $request['permintaan'],
        ]);

        return $nomer;
    }

    public function list_repair_jigu (Request $request){
        // dd($request->all());
        $draw = $request->input('draw');
        $search = $request->input('search')['value'];
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $awal = $request->input('tgl_awal');
        $akhir = $request->input('tgl_akhir');
        $status_permintaan = $request->input('status_permintaan');

        $section = $request->user()->departments->pluck('section')->toArray(); // section


        $allStatus = ['Open', 'Proses', 'Waiting User', 'Close', 'Tolak'];
        $liststatus = [];
        if ($status_permintaan == 'All') {
            $liststatus = array_merge($liststatus, $allStatus);
        } else {
            $liststatus[] = $status_permintaan;
        }

        $Datas = DB::table('tb_permintaan_kerja')
            ->whereBetween('tanggal_permintaan', [$awal, $akhir])
            ->whereIn('status', $liststatus)
            ->whereIn('dept', $section)
            ->where(function ($q) use ($search) {
                $q
                    ->where('no_laporan', 'like', '%' . $search . '%')
                    ->orwhere('nama_item', 'like', '%' . $search . '%')
                    ->orwhere('jenis_item', 'like', '%' . $search . '%')
                    ->orwhere('nama_mesin', 'like', '%' . $search . '%')
                    ->orwhere('ukuran', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'asc')
            ->skip($start)
            ->take($length)
            ->get();

        $count = DB::table('tb_permintaan_kerja')
        ->whereBetween('tanggal_permintaan', [$awal, $akhir])
        ->whereIn('status', $liststatus)
        ->whereIn('dept', $section)
        ->where(function ($q) use ($search) {
            $q
            ->where('no_laporan', 'like', '%' . $search . '%')
            ->orwhere('nama_item', 'like', '%' . $search . '%')
            ->orwhere('jenis_item', 'like', '%' . $search . '%')
            ->orwhere('nama_mesin', 'like', '%' . $search . '%')
            ->orwhere('ukuran', 'like', '%' . $search . '%');
        })
            ->count();

        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $Datas,
        ];
    }

    public function terima_jigu (Request $request){
        // dd($request->all());
        $section = $request->user()->departments->pluck('section')->first();
        $id_permintaan = $request['drj_idPermintaan'];
        $qty_selesai = $request->drj_userTerima;

        $selesai = JiguSelesaiModel::find($id_permintaan);

        if ($selesai->status == 'close') {
            return [
                'message' => 'ID salah !',
                'success' => false,
            ];
        }

        if ($qty_selesai > $selesai->qty_selesai) {
            return [
                'message' => 'Qty salah !',
                'success' => false,
            ];
        }

        $permintaan = ReqJiguModel::find($selesai->id_permintaan);
  
        if ($section == $permintaan->dept || $section == 'Admin') {
            $selesai->qty_selesai = $qty_selesai;
            $selesai->qty_ok = $qty_selesai;
            $selesai->penerima = $request->user()->name;
            $selesai->tgl_terima = Carbon::now();
            $selesai->status = 'close';
            $selesai->save();

            $permintaan->qty_selesai = $qty_selesai;
            $permintaan->status = 'Close';
            $permintaan->tanggal_selesai = Carbon::now();
            $permintaan->save();
            $statper = 'Close';

            return [
                'message' => 'Simpan Data berhasil',
                'success' => true,
            ];
        } else {            
            return [
                'message' => 'Akses ditolak !',
                'success' => false,
            ];
        }
    }
}
