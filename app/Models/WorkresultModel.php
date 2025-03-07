<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkresultModel extends Model
{
    protected $table = 'tb_workresult';
    protected $primarykey = 'id_workresult';
    public $incrementing = false;
    protected $fillable = [
        'id_workresult',
        'user_name',
        'tgl_keluar',
        'barcode_no',
        'part_no',
        'jby',
        'lot_no',
        'qty',
        'no_tag',
        'nouki',
        'start',
        'finish',
        'masalah',
        'tgl_kirim',
        'customer',
        'msk_kamu',
        'msk_f6',
        'warna_tag',
        'ord_no',
        'mld_no',
        'qty_fg',
        'y_17',
        'y_15',
        'qty_actual',
        'qty_adj',
        'tag',
        'main_inp_cd',
        'shape',
        'omo'
    ];
}
