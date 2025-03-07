<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomposisiModel extends Model
{
    protected $table = 'tb_komposisi';
    protected $primaryKey = 'id_komposisi';
    public $incrementing = false;
    protected $fillable = [
        'id_komposisi',
        'opr_qc',
        'opr_melting',
        'tgl_cek',
        'cast_no',
        'c',
        'si',
        'mn',
        'p',
        's',
        'b',
        'cu',
        'sn',
        'ni',
        'cr',
        'keterangan',
    ];
}
