<?php


namespace App\Http\Controllers\Api\Zds;


use App\Http\Controllers\Api\Transformer\Zds\BannerTransformer;
use App\Model\Zds\MpBanner;
use Dingo\Api\Http\Request;

class BannerController extends BaseController
{
    public function list(Request $request)
    {
        $banner = MpBanner::where('status', 1)->where('unicid', self::UNICID)->orderBy('id', 'desc')->get();
        return $this->response()->collection($banner, new BannerTransformer);
    }
}