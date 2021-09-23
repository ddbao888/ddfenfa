<?php


namespace App\Http\Controllers\Zds;


use App\Http\Controllers\Controller;
use App\Model\Zds\Currency;

class CurrencyController extends BaseController
{
    public function list()
    {
        return Currency::select(['id', 'name'])->get();

    }
}