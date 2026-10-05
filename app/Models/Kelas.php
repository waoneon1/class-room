<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = ['nama_kelas'];

    public function siswa()
    {
        return $this->belongsToMany(User::class, 'siswa_kelas_periode', 'kelas_id', 'user_id')
                    ->withPivot('periode_id')
                    ->where('role', 'siswa')
                    ->withTimestamps();
    }
}
