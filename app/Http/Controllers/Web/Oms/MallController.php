<?php


namespace App\Http\Controllers\Web\Oms;


use App\Http\Controllers\Web\BaseController;

class MallController extends BaseController
{
    public function index()
    {
        return $this->view('mall.index');
    }

    public function create()
    {
        return $this->view('mall.createAndEdit');
    }

}