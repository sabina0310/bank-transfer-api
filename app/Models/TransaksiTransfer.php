<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiTransfer extends Model
{
    use HasFactory;

    protected $table = 'transaksi_transfer';

    protected $fillable = [
        'id',
        'id_transaksi',
        'id_user',
        'id_bank_tujuan',
        'nomor_rekening_tujuan',
        'id_rekening_admin',
        'nilai_transfer',
        'kode_unik',
        'biaya_admin',
        'total_transfer',
        'berlaku_hingga'
    ];
}
