<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $fillable = ['attribute_id', 'value', 'start_date','end_date'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
    
}
