<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetLemburModel extends Model
{
    protected $table = 'tb_target_lembur';
    protected $primaryKey = 'id_number';
    public $incrementing = false;
    protected $fillable = [
        'id_number',
        'section_cd',
        'section',
        'target_jam',
        'periode',
        'inputor',
        'pertama',
        'kedua',
        'temp_1',
        'temp_2',
        'status_lembur',
        'ket',
    ];
}
