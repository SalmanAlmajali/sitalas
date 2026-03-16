<?php

namespace App\Models;

use App\Models\UnitPengolah;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'direktorat_id',
        'file_ttd',
        'no_hp',
        'type',
        'tkls',
        'sopd',
        'last_login',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login' => 'datetime',
            'active' => 'boolean',
            'sopd' => 'boolean',
        ];
    }

    public function unitPengolah()
    {
        return $this->belongsTo(UnitPengolah::class, 'direktorat_id');
    }

    public function scopeSopd(Builder $query): Builder
    {
        return $query->where('sopd', true);
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function isAdmin()
    {
        return $this->type === "admin";
    }

    public function isStaf()
    {
        return $this->type === "staf";
    }

    public function isUser()
    {
        return $this->type === "user";
    }
}