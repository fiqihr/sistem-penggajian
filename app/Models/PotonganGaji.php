<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotonganGaji extends Model
{
    use HasFactory;
    protected $table = 'potongan_gaji';
    protected $primaryKey = 'id_potongan_gaji';
    protected $fillable =[
        'nama_potongan',
        'jml_potongan',
    ];
}