<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'is_active',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function education()
    {
        return $this->hasMany(Education::class)->orderBy('display_order');
    }

    public function projects()
    {
        return $this->hasMany(Project::class)->orderBy('display_order');
    }

    public function skills()
    {
        return $this->hasMany(Skill::class)->orderBy('category')->orderBy('display_order');
    }

    public function awards()
    {
        return $this->hasMany(Award::class)->orderBy('display_order');
    }
}
