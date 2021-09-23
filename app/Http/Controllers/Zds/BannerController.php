<?php


namespace App\Http\Controllers\Zds;


class BannerController extends BaseController
{
    public function index()
    {
        return $this->view('setting.banner.index');
    }
}