<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Similarities extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id', 'other_movie_id', 'rating'
    ];

}
