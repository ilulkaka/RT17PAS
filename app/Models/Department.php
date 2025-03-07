<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $table = 'departments';
    protected $fillable = [
        'dept',
        'group',
        'section',
        'jenis',
        'kode_dept',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
