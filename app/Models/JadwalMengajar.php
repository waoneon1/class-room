<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalMengajar extends Model
{
    protected $table = 'jadwal_mengajar';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'mata_pelajaran_id',
        'periode_id',
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
