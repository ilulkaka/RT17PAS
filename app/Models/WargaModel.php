<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WargaModel extends Model
{
    protected $table = 'tb_warga';
    protected $primaryKey = 'id_warga';
    public $incrementing = false;
    protected $fillable = [
        'id_warga',
        'no_kk',
        'no_ktp',
        'nama',
        'alamat_ktp',
        'blok',
        'jenis_kelamin',
        'no_telp',
        'status_tinggal',
        'keterangan',
    ];
}
