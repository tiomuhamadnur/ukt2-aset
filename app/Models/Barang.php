<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function kontrak()
    {
        return $this->belongsTo(Kontrak::class);
    }

    public function seksi()
    {
        return $this->belongsTo(Seksi::class);
    }

    public function pengiriman_barang()
    {
        return $this->hasMany(PengirimanBarang::class, 'barang_id');
    }
}
