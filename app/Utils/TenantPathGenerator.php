<?php

namespace App\Utils;

use Hyn\Tenancy\Facades\TenancyFacade;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator as SpatiePathGenerator;

class TenantPathGenerator implements SpatiePathGenerator
{
    /*
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        $website  = TenancyFacade::website();
        $prefix = '/storage/tenancy/tenants/'.$website->uuid;
        return $prefix . '/' . $media->id . '/';
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . '/conversions/';
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . '/responsive-images/';
    }
}