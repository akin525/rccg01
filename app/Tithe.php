<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tithe extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'date'
    ];
}
