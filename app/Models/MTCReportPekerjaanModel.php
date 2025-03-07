<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTCReportPekerjaanModel extends Model
{
    protected $table = 'tb_mtc_report_pekerjaan';
    protected $primaryKey = 'id_mtc_report_pekerjaan';
    public $incrementing = true;
    protected $fillable = [
        'id_report_pekerjaan',
        'tgl_pekerjaan',
        'shift',
        'nik',
        'deskripsi',
        'status_pekerjaan',
        'approve',
        'tgl_approve',
        'deskripsi_2',
    ];
}
