<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeginningModel extends Model
{
    protected $table = 'tb_beginning';
    protected $primaryKey = 'id_beginning';
    public $incrementing = false;
    protected $fillable = [
        'id_beginning',
        'periode',
        'nominal',
        'seq',
        'input_by',
    ];
}
