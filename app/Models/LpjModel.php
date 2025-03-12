<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpjModel extends Model
{
    protected $table = 'tb_lpj';
    protected $primaryKey = 'id_lpj';
    public $incrementing = false;
    protected $fillable = [
        'id_lpj',
        'tgl_transaksi',
        'deskripsi',
        'jenis',
        'nominal',
        'pic',
        'keterangan',
        'seq',
        'input_by',
    ];
}
