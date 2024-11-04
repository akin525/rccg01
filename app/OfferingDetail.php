<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferingDetail extends Model
{
    use HasFactory;
    protected $table = 'offering_details';
    protected $guarded=[];
    public function details()
    {
        return $this->hasMany(OfferingDetail::class);
    }

}
