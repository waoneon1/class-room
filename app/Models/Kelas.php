<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = ['nama_kelas'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
