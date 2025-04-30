<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    protected $table = 'guru';
    protected $primaryKey = 'id_guru';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }
}