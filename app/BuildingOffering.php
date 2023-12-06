<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildingOffering extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'date'
    ];
}
