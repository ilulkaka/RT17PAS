<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SleeveModel extends Model
{
    protected $table = 'tb_permintaan_sleeve';
    protected $primaryKey = 'id_permintaan_sleeve';

    protected $fillable = [
        'id_permintaan_sleeve',
        'tgl_request',
        'jenis',
        'item',
        'qty',
        'nouki',
        'keterangan',
        'requested',
        'pengirim',
        'qty_ok',
        'tgl_kirim',
        'barcode_no',
        'status_ps',
    ];
}
