<?php


namespace App\Http\Controllers\Api\Transformer\Zds;

use App\Model\Zds\MpBanner;
use League\Fractal\TransformerAbstract;

class BannerTransformer extends TransformerAbstract
{
    public function transform(MpBanner $mpBanner)
    {
        return [
            'id' => $mpBanner->uuid,
            'title' => $mpBanner->title,
            'image' => $mpBanner->pic,
            'page' => $mpBanner->page
        ];
    }
}