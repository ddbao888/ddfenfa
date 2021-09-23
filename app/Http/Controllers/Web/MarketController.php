<?php


namespace App\Http\Controllers\Web;


class MarketController extends BaseController
{


    public function index()
    {
        return view('web.market.index');
    }

    public function create()
    {
        return view('web.mall.createAndEdit');
    }

}