<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumpulanFile extends Model
{
    protected $fillable = [
        'pengumpulan_id',
        'file_path',
        'urutan',
    ];

    public function pengumpulan()
    {
        return $this->belongsTo(Pengumpulan::class);
    }
}
