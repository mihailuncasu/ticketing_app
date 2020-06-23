<?php

namespace App\Utils;

use DateTimeInterface;
use Hyn\Tenancy\Facades\TenancyFacade;
use Spatie\MediaLibrary\UrlGenerator\BaseUrlGenerator;

class TenantUrlGenerator extends BaseUrlGenerator
{
    /**
     * Get the url for the profile of a media item.
     *
     * @return string
     */
    public function getUrl() : string
    {
        $website  = TenancyFacade::website();
        $prefix = '/storage/tenancy/tenants/'.$website->uuid;
        return $prefix.'/'.$this->getPathRelativeToRoot();
    }

    /**
     * Get the temporary url for a media item.
     *
     * @param \DateTimeInterface $expiration
     * @param array $options
     *
     * @return string
     */
    public function getTemporaryUrl(DateTimeInterface $expiration, array $options = []): string
    {
        return $this
            ->filesystemManager
            ->disk($this->media->disk)
            ->temporaryUrl($this->getPath(), $expiration, $options);
    }

    /**
     * Get the url to the directory containing responsive images.
     *
     * @return string
     */
    public function getResponsiveImagesDirectoryUrl(): string
    {
        $website  = TenancyFacade::website();
        $prefix = '/storage/tenancy/tenants/'.$website->uuid;
        return $prefix.'/'.$this->pathGenerator->getPathForResponsiveImages($this->media);
    }
}