<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElastisitasModel extends Model
{
    protected $table = 'tb_elastisitas';
    protected $primaryKey = 'id_elastisitas';
    public $incrementing = false;
    protected $fillable = [
        'id_elastisitas',
        'tgl_proses',
        'part_no',
        'lot_no',
        'cast_no',
        'mikrostruktur',
        'ketetapan',
        'leadle_no',
        'hard_1',
        'hard_2',
        'hard_3',
        'hard_4',
        'hard_5',
        'diameter',
        'b1',
        'b2',
        'b3',
        'b4',
        'b5',
        't1',
        't2',
        't3',
        't4',
        't5',
        's',
        'w1',
        'w2',
        'w3',
        'w4',
        'w5',
        'e1',
        'e2',
        'e3',
        'e4',
        'e5',
        'avg_e',
        'ka_1',
        'ka_2',
        'ringkasan',
        'keterangan',
        'barcode_no',
        'pemeriksa',
    ];
}
