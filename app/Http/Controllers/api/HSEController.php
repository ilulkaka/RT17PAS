<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HHModel;
use Illuminate\Support\Facades\DB;

class HSEController extends Controller
{
    public function get_hhky($thn){
        // $hhky = DB::select("select jenis_laporan, sum(case when status_laporan = 'Close' then 1 else 0 end) as close1, count(no_laporan) as masuk from tb_hhky
        //                     where year(tgl_lapor) = ?
        //                     group by jenis_laporan ",[$thn]);

        $hhky = DB::select("select t1.bagian,isnull((t2.ky_masuk),0) as ky_masuk,isnull((t2.ky_close),0) as ky_close, isnull((t2.hh_masuk),0) as hh_masuk,isnull((t2.hh_close),0) as hh_close  from
                            (select bagian from tb_hhky where jenis_laporan != 'SM' group by bagian) t1
                            left join
                            (select bagian, sum( case when jenis_laporan = 'KY' then 1 else 0 end) as ky_masuk,sum(case when jenis_laporan = 'KY' and status_laporan = 'Close' then 1 else 0 end) as ky_close, sum( case when jenis_laporan = 'HH' then 1 else 0 end) as hh_masuk,sum(case when jenis_laporan = 'HH' and status_laporan = 'Close' then 1 else 0 end) as hh_close from tb_hhky
                            where year(tgl_lapor) = ?
                            group by bagian) t2 on t1.bagian = t2.bagian",[$thn]);
        
        return [
            "success"=> true,
            "data"=>$hhky
        ];
    }
}
