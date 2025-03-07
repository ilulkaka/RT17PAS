<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeasurementStModel extends Model
{
    protected $table = 'tb_meas_st';
    protected $primaryKey = 'id_meas_st';
    public $incrementing = false;
    protected $fillable = [
        'id_meas_st',
        'id_meas',
        'no_registrasi',
        'tgl_terima',
        'tgl_kalibrasi',
        'lokasi',
        'rak_no',
        'warna_identifikasi',
        'so_st',
        'keterangan',
        'tgl_penyerahan',
        'tgl_penarikan',
        'penerima',
        'section',
        'range_cek',
        'abnormalitas',
    ];
}
