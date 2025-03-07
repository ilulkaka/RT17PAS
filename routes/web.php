<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\api\PPICController;
use App\Http\Controllers\api\TECHController;
use App\Http\Middleware\CheckAbility;

Route::get('/login',[PageController::class,'login'])->middleware('guest')->name('login');
Route::get('/logout',[UserController::class,'logout'])->name('logout');
Route::post('/postlogin', [Usercontroller::class,'authenticate']);
Route::get('/sesion', [Usercontroller::class,'sesion']);

//Route::get('/', [PageController::class,'home'])->middleware('auth:sanctum')->name('home');

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/undermaintenance', [PageController::class,'undermaintenance']);
    Route::get('/', [PageController::class,'home'])->name('home');
    Route::get('/profile', [PageController::class,'profile']);
    Route::get('produksi/ngreport', [PageController::class,'ngreport']);

    Route::get('produksi/pointperubahan', [PageController::class,'pointperubahan']);
    Route::get('produksi/formmasalah', [PageController::class,'formmasalah']);
    Route::get('produksi/report_produksi', [PageController::class,'menu_hasil_produksi']);
    Route::get('produksi/report_lembur', [PageController::class,'report_lembur']);
    Route::get('produksi/menu_request_jigu', [PageController::class,'menu_request_jigu']);
    Route::get('produksi/list_permintaan_perbaikan', [PageController::class,'listPermintaanPerbaikan']);
    Route::get('produksi/report_produksi/hasil_produksi', [PageController::class,'list_report_produksi']);
    Route::get('produksi/report_produksi/approve_jam_operator', [PageController::class,'approve_jam_operator']);
    Route::get('produksi/report_produksi/grafik_hasil_produksi', [PageController::class,'grafik_hasil_produksi']);
    Route::get('produksi/report_produksi/rekap_produksiNsales', [PageController::class,'rekap_produksiNsales']);
    Route::get('produksi/report_produksi/list_shikakari', [PageController::class,'list_shikakari'])->name('list_shikakari');
    Route::get('produksi/menu_request_jigu/frm_repair_jigu', [PageController::class,'frm_repair_jigu'])->name('frm_repair_jigu');
    Route::get('produksi/menu_request_jigu/list_measurement', [PageController::class,'list_measurement'])->name('list_measurement');
    Route::get('produksi/frm_foundry', [PageController::class,'frm_foundry']);
    Route::get('produksi/frm_foundry/frm_pouring', [PageController::class,'frmPouring']);
    Route::get('produksi/frm_foundry/frm_elastisitas', [PageController::class,'frmElastisitas']);
    Route::get('produksi/frm_foundry/list_permintaan_sleeve', [PageController::class,'listPermintaanSleeve']);
    Route::get('produksi/frm_foundry/cetak_permintaan_sleeve/{id}', [PageController::class,'cetakPermintaanSleeve']);
    Route::get('produksi/frm_foundry/frm_komposisi', [PageController::class,'frmKomposisi']);

    // ======= Maintenance ========================
    // Route::get('maintenance/schedule', [PageController::class,'schedule']);

    Route::post('ppic/upd_expres',[PPICController::class,'upd_expres'])->name('ppic.upd_expres');
    Route::post('produksi/menu_request_jigu/inp_req_jigu',[TECHController::class,'inp_req_jigu'])->name('produksi/menu_request_jigu/inp_req_jigu');

});

Route::middleware(['auth:sanctum','ability:admin,only_maintenance'])->group(function(){
    Route::get('maintenance/schedule', [PageController::class,'schedule']);
});

Route::middleware(['checkability:admin'])->group(function(){
    Route::get('admin/userlist', [PageController::class,'userlist'])->name('userlist');
    Route::get('admin/register', [PageController::class,'register']);
});

Route::middleware(['checkability:admin'])->group(function(){

});


// Route::get('/', function () {
//     return view('welcome');
// });
