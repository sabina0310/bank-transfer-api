<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningAdmin extends Model
{
    use HasFactory;

    protected $table = 'rekening_admin';

    protected $fillable = [
        'id_bank',
        'nomor_rekening',
        'atas_nama_rekening'
    ];
}
