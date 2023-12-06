<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class preacherattendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'preachername',
        'service_types_id',
        'attendance_date'
    ];

    public function service_types(){
        return $this->belongsTo(ServiceType::class);
      }
}
