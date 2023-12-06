<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'groupname',
        'service_types_id',
        'attendance_date'
    ];
}
