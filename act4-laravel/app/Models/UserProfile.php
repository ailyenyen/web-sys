<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'phone',
        'location',
        'linkedin',
        'github',
        'summary',
        'languages',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
