<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrolment extends Model
{
    use HasFactory;
    protected $fillable = [
        'tutoring_class_id',
        'user_id'
    ];
}
