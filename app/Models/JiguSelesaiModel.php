<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JiguSelesaiModel extends Model
{
    protected $table = 'tb_jigu_selesai';
    protected $primaryKey = "id_permintaan";
    public $incrementing = false;
    protected $fillable = [
        'id_jigu_selesai',
        'id_permintaan',
        'tgl_selesai',
        'qty_selesai',
        'penerima',
        'tgl_terima',
        'status',
        'qty_ok',
        'operator_tch',
    ];
}
