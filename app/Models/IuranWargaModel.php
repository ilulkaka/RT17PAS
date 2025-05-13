<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IuranWargaModel extends Model
{
    protected $table = 'tb_iuran';
    protected $primaryKey = 'id_iuran';
    public $incrementing = false;
    protected $fillable = [
        'id_iuran',
        'nama',
        'blok',
        'periode',
        'nominal',
        'inputor',
        'tgl_bayar'
    ];
}
