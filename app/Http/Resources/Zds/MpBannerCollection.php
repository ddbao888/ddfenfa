<?php

namespace App\Http\Resources\Zds;

use App\Http\Resources\BaseCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MpBannerCollection extends BaseCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
