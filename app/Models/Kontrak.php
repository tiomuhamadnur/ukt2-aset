<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kontrak extends Model
{
    use HasFactory;

    protected $table = 'kontrak';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function canBeDeleted()
    {
        return $this->barang->isEmpty();
    }

    public function seksi()
    {
        return $this->belongsTo(Seksi::class);
    }

    public function barang()
    {
        return $this->hasMany(Barang::class, 'kontrak_id');
    }
}
