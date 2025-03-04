<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'status',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_users')->withTimestamps();
    }


    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_values', 'entity_id', 'attribute_id')
            ->withPivot('value')
            ->withTimestamps();
    }
}
