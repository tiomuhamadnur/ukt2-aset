<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Mchev\Banhammer\Traits\Bannable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Bannable;

    protected $fillable = [
        'name',
        'email',
        'password',
        "nip",
        "phone",
        "gender",
        "tempat_lahir",
        "tanggal_lahir",
        "alamat",
        "role_id",
        "jabatan_id",
        "employee_type_id",
        "area_id",
        "struktur_id",
        "photo",
        "bio",
        "ttd",
        "active",
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = str::uuid();
        });
    }

    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function struktur()
    {
        return $this->belongsTo(Struktur::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function employee_type()
    {
        return $this->belongsTo(EmployeeType::class);
    }

    public function konfigurasi_cuti()
    {
        return $this->hasOne(KonfigurasiCuti::class);
    }
}
