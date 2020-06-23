<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;


class Group extends Model
{
     use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'guard_name', 'updated_at', 'description', 'created_by'
    ];

    public function users()
    {
        return $this
            ->belongsToMany(User::class)
            ->withPivot(['added_by'])
            ->withTimestamps();
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'unique' => true
            ]
        ];
    }
}
