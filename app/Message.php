<?php

namespace App;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'user_id', 'group_id', 'text', 'updated_at'
    ];

    protected $hidden = [
        'group_id'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
