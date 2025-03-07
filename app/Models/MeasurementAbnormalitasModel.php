<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeasurementAbnormalitasModel extends Model
{
    protected $table = 'tb_meas_abnormalitas';
    protected $primaryKey = 'id_meas_abnormalitas';
    public $incrementing = false;
    protected $fillable = [
        'id_meas_abnormalitas',
        'id_meas',
        'id_meas_st',
        'tgl_temuan',
        'tgl_pemakaian',
        'lama_pemakaian',
        'operator',
        'masalah',
        'penyebab',
        'dibuat',
        'tindakan',
        'keterangan',
        'no_reg_pengganti',
        'hasil_kalibrasi',
        'opr_qa',
        'periksa_qa',
        'tgl_tindakan',
    ];
}
