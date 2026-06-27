<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tugas extends Model
{
    use SoftDeletes;

    protected $table = 'tugas';

    protected $fillable = [
        'guru_id',
        'mata_pelajaran_id',
        'judul',
        'deskripsi',
        'file_tugas',
        'deadline',
        'semester',
        'tahun_ajaran',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
        ];
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function pengumpulan()
    {
        return $this->hasMany(Pengumpulan::class);
    }
}
