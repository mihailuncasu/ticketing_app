<?php

namespace App;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Spatie\MediaLibrary\Models\Media as SpatieMedia;

class Media extends SpatieMedia
{
    use UsesTenantConnection;
}