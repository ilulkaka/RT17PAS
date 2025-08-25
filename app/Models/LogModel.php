<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogModel extends Model
{
    protected $table = 'tb_log';
    protected $primaryKey = 'id_log';
    public $incrementing = false;
    protected $fillable = [
        'id_log',
        'user_id',
        'user_name',
        'activity',
        'message',
    ];
}
