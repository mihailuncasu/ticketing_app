<?php

namespace App;

use Hyn\Tenancy\Abstracts\SystemModel;
use Hyn\Tenancy\Contracts\Website as WebsiteContract;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Website extends SystemModel implements WebsiteContract
{
    use SoftDeletes;

    /**
    * Get all of the hostnames for the Website.
    *
    * @return \Illuminate\Database\Eloquent\Collection
    */
    public function hostnames(): HasMany
    {
        return $this->hasMany(config('tenancy.models.hostname'));
    }

}
