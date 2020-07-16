<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'title', 'description', 'updated_at', 'display_name', 'uuid', 'priority', 'status', 'group_slug', 'created_by', 'assigned_to'
    ];

    protected $hidden = [
        'created_by', 'assigned_to'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
