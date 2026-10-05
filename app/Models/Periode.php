<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_periode',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function materi()
    {
        return $this->hasMany(Materi::class);
    }
}
