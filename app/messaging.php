<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class messaging extends Model
{
    protected $fillable = [
        'msg_to',
        'msg_from',
        'msg',
        'subject'
    ];
}
