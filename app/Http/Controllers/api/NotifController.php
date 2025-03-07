<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifController extends Controller
{
    public function notifPermintaanSleeve (Request $request)
    {
        $notif_sleeve = DB::table('tb_permintaan_sleeve')
        ->where('status_ps', '=', 'Open')
        ->get();

    return [
        'notif_sleeve' => count($notif_sleeve),
    ];
    }
}
