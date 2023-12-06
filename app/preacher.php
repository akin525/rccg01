<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class preacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
    ];
}
