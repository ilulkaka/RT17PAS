<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\SleeveModel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

class PageController extends Controller
{
    public function login(){
        return view('login-page');
    }

    public function home(){

        return view('userPage.dashboard');
    }

    public function profile(){
        return view('userPage.userprofil');
    }


//===================produksi==================

    public function pointperubahan(){
        return view('iso.frm_pointperubahan');
    }
    public function ngreport(){
        return view('produksi.ngreport');
    }
    public function formmasalah(){
        return view('produksi.formmasalah');
    }
    public function menu_hasil_produksi(){
        return view('produksi.menu_hasil_produksi');
    }
    public function report_lembur(){
        return view('produksi.report_lembur');
    }
    public function menu_request_jigu(){
        return view('produksi.menu_request_jigu');
    }
    public function listPermintaanPerbaikan(){
        return view('produksi.list_permintaan_perbaikan');
    }

    public function list_report_produksi(){
        return view('produksi.list_report_produksi');
    }

    public function approve_jam_operator(){
        return view('produksi.approve_jam_operator');
    }

    public function grafik_hasil_produksi(){
        return view('produksi.grafik_hasil_produksi');
    }

    public function rekap_produksiNsales(){
        return view('produksi.rekap_produksiNsales');
    }

    public function list_shikakari(){
        return view('produksi.list_shikakari');
    }

    public function frm_repair_jigu(){
        return view('produksi.frm_repair_jigu');
    }

    public function list_measurement(){
        return view('produksi.list_measurement');
    }

    public function frm_foundry(){
        return view('produksi.frm_foundry');
    }

    public function frmPouring(Request $request){
        $section = $request->user()->departments->pluck('section')->toArray();

        if (in_array("CASTING", $section)) {
            return view('produksi.frm_pouring');
        } else {
            return redirect()->back()->with('error', 'Only Casting Section.');
        }
    }

    public function frmElastisitas(Request $request){
        $section = $request->user()->departments->pluck('section')->toArray();

        if (in_array("CASTING", $section)) {
            return view('produksi.frm_elastisitas');
        } else {
            return redirect()->back()->with('error', 'Only Casting Section.');
        }
    }

    public function listPermintaanSleeve(Request $request){
        $section = $request->user()->departments->pluck('section')->toArray();

        if (in_array("CASTING", $section)) {
            return view('produksi.list_permintaan_sleeve');
        } else {
            return redirect()->back()->with('error', 'Only Casting Section.');
        }
    }

    public function cetakPermintaanSleeve($id){
        $req = SleeveModel::find($id);

        $pdf = PDF::loadview('/produksi/pdf_permintaan_sleeve', ['list' => $req]);
        return $pdf->stream('Form Request Jigu.pdf');
    }

    public function frmKomposisi(){
        return view('produksi.frm_komposisi');
    }

    //==================== Maintenance ====================
    public function schedule(){
        return view('maintenance.frm_schedule');
    }

//====================admin====================
    public function userlist(){
        return view('admin.userlist');
    }

    public function register(){
        return view('admin.register');
    }
//====================iso======================


    public function underMaintenance ()
    {
        return view ('undermaintenance');
    }

    public function frmIuranWarga(){
        return view('keuangan.frm_iuran_warga');
    }

    public function listWarga(){
        return view('datas.list_warga');
    }

 
}
