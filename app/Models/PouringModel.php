<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PouringModel extends Model
{
    protected $table = 'tb_hasil_pouring';
    protected $primaryKey = 'id_hasil_pouring';
    public $incrementing = false;
    protected $fillable = [
        'id_hasil_pouring',
        'tgl_proses',
        'barcode_no',
        'part_no',
        'lot_no',
        'cast_no',
        'leadle_no',
        'mesin_no',
        'yama',
        'defect',
        'keterangan',
        'user',
        'nik',
        'opr_name',
        'keterangan_1',
    ];
}
