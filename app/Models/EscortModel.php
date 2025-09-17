<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscortModel extends Model
{
    use HasFactory;
    
    protected $table = 'escorts';
    
    protected $fillable = [
        'kategori_pengantar',
        'nama_pengantar',
        'jenis_kelamin',
        'nomor_hp',
        'plat_nomor',
        'nama_pasien',
        'foto_pengantar',
        'submission_id',
        'submitted_from_ip',
        'api_submission'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
