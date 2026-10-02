<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Periode;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'nama_lengkap',
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function rombel()
    {
        return $this->belongsToMany(Kelas::class, 'siswa_kelas_periode')
                    ->withPivot('periode_id')
                    ->withTimestamps();
    }

    public function jadwalMengajar()
    {
        return $this->hasMany(JadwalMengajar::class, 'user_id');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'guru_id');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'guru_id');
    }

    public function pengumpulan()
    {
        return $this->hasMany(Pengumpulan::class, 'siswa_id');
    }

    public function getKelasAttribute()
    {
        if ($this->role === 'siswa') {
            $periodeId = Periode::where('is_active', true)->value('id');
            return $this->rombel()->wherePivot('periode_id', $periodeId)->first();
        }
        return null;
    }
}
