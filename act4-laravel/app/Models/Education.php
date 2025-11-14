<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'education';

    protected $fillable = [
        'user_id',
        'degree',
        'school',
        'start_date',
        'end_date',
        'gpa',
        'display_order',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
