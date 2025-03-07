<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Users;
use App\Http\Controllers\api\Dashboard;
use App\Http\Controllers\api\ProduksiController;
use App\Http\Controllers\api\HSEController;
use App\Http\Controllers\api\MTCController;
use App\Http\Controllers\api\PGAController;
use App\Http\Controllers\api\TECHController;
use App\Http\Controllers\api\MeasurementController;
use App\Http\Controllers\api\FoundryController;
use App\Http\Controllers\api\NotifController;

use App\Http\Controllers\api\DatasController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::middleware(['auth:sanctum','abilities:admin'])->group(function(){
    //============user============
    Route::get('admin/getUserByNik/{nik}',[Users::class,'getUserByNik']);
    Route::get('admin/userlist',[Users::class,'userlist']);
    Route::post('admin/createuser',[Users::class,'createuser']);
    Route::delete('admin/del_user/{id}',[Users::class,'del_user']);
    Route::put('admin/edit_user',[Users::class,'edit_user']);
    Route::post('admin/resetpassword',[Users::class,'resetpassword']);
    //=========dept=============
    Route::get('admin/deptlist',[Users::class,'deptlist']);
    Route::post('admin/createdept',[Users::class,'createdept']);
    Route::delete('admin/del_dept/{id}',[Users::class,'del_dept']);
    Route::put('admin/edit_dept',[Users::class,'edit_dept']);
    Route::get('admin/alldept',[Users::class,'getAllDept']);
    //=========permission==========
    Route::get('admin/permissionlist',[Users::class,'permissionlist']);
    Route::get('admin/allpermission',[Users::class,'getAllPermission']);
    Route::post('admin/createpermit',[Users::class,'createpermit']);
    Route::put('admin/edit_permit',[Users::class,'edit_permit']);
    Route::delete('admin/del_permit/{id}',[Users::class,'del_permit']);
    //==========role===============
    Route::get('admin/rolelist',[Users::class,'rolelist']);
    Route::post('admin/createrole',[Users::class,'createrole']);
    Route::put('admin/edit_role',[Users::class,'edit_role']);
    Route::delete('admin/del_role/{id}',[Users::class,'del_role']);
    Route::get('admin/allrole',[Users::class,'getAllRole']);

});

Route::middleware(['auth:sanctum','ability:admin,list_jam_operator'])->group(function(){
    Route::get('produksi/l_approve_jam_operator',[ProduksiController::class,'l_approve_jam_operator']);
    Route::get('produksi/l_approve_jam_operator_1',[ProduksiController::class,'l_approve_jam_operator_1']);
});

Route::middleware(['auth:sanctum','ability:admin,edit_jam_operator'])->group(function(){
    Route::patch('produksi/edit_jamkerjaoperator',[ProduksiController::class,'edit_jamkerjaoperator']);
});

Route::middleware(['auth:sanctum','ability:admin,approve_jam_operator'])->group(function(){
    Route::patch('produksi/approve_jam_kerja',[ProduksiController::class,'approve_jam_kerja']);
});

Route::middleware(['auth:sanctum','ability:admin,approve_jam_operator'])->group(function(){
    Route::patch('produksi/approve_jam_kerja',[ProduksiController::class,'approve_jam_kerja']);
});

Route::middleware(['auth:sanctum','ability:admin,upd_next_process'])->group(function(){
    Route::patch('produksi/upd_next_process',[ProduksiController::class,'upd_next_process']);
});

Route::middleware(['auth:sanctum','ability:admin,create_planning_lembur,edit_planning_lembur'])->group(function(){
    Route::post('pga/create_planning_lembur',[PGAController::class,'create_planning_lembur']);
    Route::post('pga/edit_planning_lembur',[PGAController::class,'edit_planning_lembur']);
});

Route::middleware(['auth:sanctum','ability:admin,approve_penerimaan_alat_ukur'])->group(function(){
    Route::patch('meas/upd_selected_approve',[MeasurementController::class,'updSelectedApprove']);
});

Route::middleware(['auth:sanctum','ability:admin,pelaporan_jigu_abnormal'])->group(function(){
    Route::patch('meas/upd_report_abnormal',[MeasurementController::class,'updReportAbnormal']);
});

Route::middleware(['auth:sanctum','ability:admin,del_data_komposisi'])->group(function(){
    Route::delete('foundry/del_komposisi/{id}',[FoundryController::class,'delKomposisi']);
});

Route::middleware(['auth:sanctum','ability:admin,only_maintenance'])->group(function(){
    Route::post('mtc/ins_schedule',[MTCController::class,'insSchedule']);
    Route::get('mtc/list_sch_pemeriksaan',[MTCController::class,'listSchPemeriksaan']);
    Route::post('mtc/upl_lampiran',[MTCController::class,'uplLampiran']);
    Route::get('mtc/apr_hasil_pekerjaan',[MTCController::class,'aprHasilPekerjaan']);
    Route::get('mtc/list_hasil_perbaikan',[MTCController::class,'listHasilPerbaikan']);
    Route::patch('mtc/upd_hasil_pekerjaan',[MTCController::class,'updHasilPekerjaan']);
});


Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('user/n_info_upd',[Users::class,'n_info_upd']);
    Route::get('user/c_info_upd',[Users::class,'c_info_upd']);
    Route::post('user/update-pass',[Users::class,'update_pass']);
    //=====================hse==================================
    Route::get('hse/get_hhky/{thn}',[HSEController::class,'get_hhky']);
    
    //====================produksi==========================
    Route::get('produksi/getline',[ProduksiController::class,'getline']);
    Route::get('produksi/getwarna',[ProduksiController::class,'getwarna']);
    Route::get('produksi/g_shikakari',[ProduksiController::class,'g_shikakari']);
    Route::post('produksi/g_hasilproduksi',[ProduksiController::class,'g_hasilproduksi']);
    Route::get('produksi/listng',[ProduksiController::class,'listng']);
    Route::get('produksi/inquery_report_detail',[ProduksiController::class,'inquery_report_detail']);
    Route::get('produksi/detail_ng',[ProduksiController::class,'detail_ng']);
    Route::get('produksi/get_excel_hasilproduksi',[ProduksiController::class,'get_excel_hasilproduksi']);
    Route::get('produksi/grafik_opr_finish_qty',[ProduksiController::class,'grafik_opr_finish_qty']);
    Route::get('produksi/detail_hasil_jam_opr',[ProduksiController::class,'detail_hasil_jam_opr']);
    Route::get('produksi/detail_hasil_fcr_opr',[ProduksiController::class,'detail_hasil_fcr_opr']);
    Route::get('produksi/get_rekap_produksiNsales',[ProduksiController::class,'get_rekap_produksiNsales']);
    Route::get('produksi/get_rekapHasilProduksi',[ProduksiController::class,'get_rekapHasilProduksi']);

    Route::get('produksi/list_shikakari',[ProduksiController::class,'list_shikakari']);
    Route::get('produksi/excel_shikakari',[ProduksiController::class,'excel_shikakari']);

    //=============dashboard==================================
    Route::get('dashboard/get_dataDashboard',[Dashboard::class,'get_dataDashboard']);
    Route::get('dashboard/get_laporproduksi',[Dashboard::class,'get_laporproduksi']);
    Route::get('dashboard/get_successrate',[ProduksiController::class,'get_successrate']);

    //================Maintenance==================================
    Route::get('maintenance/grafikjam/{thn}',[MTCController::class,'grafikjam']);
    Route::post('maintenance/detailjam',[MTCController::class,'detailjam']);
    Route::get('mtc/list_permintaan_perbaikan',[MTCController::class,'listPermintaanPerbaikan']);
    Route::get('mtc/notif_permintaan_perbaikan',[MTCController::class,'notifPermintaanPerbaikan']);
    Route::get('mtc/nomer_permintaan_perbaikan',[MTCController::class,'nomerPermintaanPerbaikan']);
    Route::get('mtc/nomer_induk_mesin',[MTCController::class,'nomerIndukMesin']);
    Route::post('mtc/ins_permintaan_perbaikan_mesin',[MTCController::class,'insPermintaanPerbaikanMesin']);
    Route::patch('mtc/apr_permintaan_reject',[MTCController::class,'aprPermintaanReject']);
    Route::patch('mtc/edt_permintaan_perbaikan',[MTCController::class,'edtPermintaanPerbaikan']);
    Route::delete('mtc/del_permintaan_perbaikan/{id}',[MTCController::class,'delPermintaanPerbaikan']);
    Route::patch('mtc/upd_terima_perbaikan/{id}',[MTCController::class,'updTerimaPerbaikan']);

    //================ PGA ==================================
    Route::get('PGA/grafik_lembur',[PGAController::class,'grafik_lembur']);
    Route::get('PGA/detail_lembur',[PGAController::class,'detail_lembur']);
    Route::get('PGA/planning_lembur',[PGAController::class,'planning_lembur']);

    //================ TECH ==================================
    Route::get('tech/get_namaMesin',[TECHController::class,'get_namaMesin']);
    Route::get('tech/list_repair_jigu',[TECHController::class,'list_repair_jigu']);
    Route::get('tech/no_req_jigu',[TECHController::class,'no_req_jigu']);
    Route::get('tech/notif_req_jigu',[TECHController::class,'notif_req_jigu']);
    Route::patch('tech/terima_jigu',[TECHController::class,'terima_jigu']);

    //================ MEASUREMENT ==================================
    Route::get('meas/list_approve',[MeasurementController::class,'listApprove']);
    Route::get('meas/list_production',[MeasurementController::class,'listProduction']);

    //================ FOUNDRY ==================================
    Route::get('foundry/get_nik_casting',[FoundryController::class,'getNikCasting']);
    Route::get('foundry/get_no_cast',[FoundryController::class,'getNoCast']);
    Route::get('foundry/get_barcode_pouring',[FoundryController::class,'getBarcodePouring']);
    Route::post('foundry/ins_pouring',[FoundryController::class,'insPouring']);
    Route::get('foundry/list_pouring',[FoundryController::class,'listPouring']);
    Route::post('foundry/ins_mikrostruktur',[FoundryController::class,'insMikrostruktur']);
    Route::patch('foundry/edt_pouring',[FoundryController::class,'edtPouring']);
    Route::delete('foundry/del_hasil_pouring/{id}/{lot}',[FoundryController::class,'delHasilPouring']);
    Route::get('foundry/xls_pouring',[FoundryController::class,'xlsPouring']);
    Route::get('foundry/list_elastisitas',[FoundryController::class,'listElastisitas']);
    Route::post('foundry/ins_mohan',[FoundryController::class,'insMohan']);
    Route::patch('foundry/upd_hardness',[FoundryController::class,'updHardness']);
    Route::patch('foundry/edt_hardness',[FoundryController::class,'edtHardness']);
    Route::patch('foundry/upd_elastisitas',[FoundryController::class,'updElastisitas']);
    Route::patch('foundry/edt_elastisitas',[FoundryController::class,'edtElastisitas']);
    Route::delete('foundry/del_elastisitas/{id}',[FoundryController::class,'delElastisitas']);
    Route::get('foundry/list_permintaan_sleeve',[FoundryController::class,'listPermintaanSleeve']);
    Route::patch('foundry/proses_permintaan_sleeve',[FoundryController::class,'ProsesPermintaanSleeve']);
    Route::post('foundry/ins_komposisi',[FoundryController::class,'insKomposisi']);
    Route::get('foundry/list_komposisi',[FoundryController::class,'listKomposisi']);
    
    
    
    //================ NOTIF ==================================
    Route::get('foundry/notif_permintaan_sleeve',[NotifController::class,'notifPermintaanSleeve']);
    
});

Route::middleware(['auth:sanctum','ability:admin,del_data_komposisi'])->group(function(){
    Route::post('datas/ins_warga',[DatasController::class,'insWarga']);
    Route::get('datas/list_warga',[DatasController::class,'listWarga']);
});