<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeasurementModel extends Model
{
    protected $table = 'tb_meas';
    protected $primaryKey = 'id_meas';
    public $incrementing = false;
    protected $fillable = [
        'id_meas',
        'kode',
        'no_registrasi',
        'nama_alat_ukur',
        'ukuran',
        'jenis',
        'serial',
        'maker',
        'range_cek',
        'lokasi',
        'rak_no',
        'status_meas',
        'keterangan',
        'tgl_masuk',
        'tgl_keluar',
        'movement',
        'qc',
        'section',
        'tgl_ng',
    ];
}
