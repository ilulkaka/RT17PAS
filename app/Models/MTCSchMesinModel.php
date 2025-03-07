<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MTCSchMesinModel extends Model
{
    protected $table = 'tb_sch_mesin_tahunan';
    protected $primaryKey = 'id_perbaikan';

    protected $fillable = [
        'id_sch_mesin_tahunan',
        'id_perbaikan',
        'no_perbaikan',
        'no_induk_mesin',
        'nama_mesin',
        'jadwal_perbaikan',
        'lampiran',
        'keterangan',
        'jenis_perbaikan',
    ];
}
