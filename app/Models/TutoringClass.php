<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutoringClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'teacher_id',
        'subject'
    ];


    // for many to many relationship
    public function user() {
        return $this->belongsToMany(User::class, 'enrolments')->withTimestamps();
    }
}
