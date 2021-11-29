<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactRequests extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'message',
        'is_resolved'
    ];
}
