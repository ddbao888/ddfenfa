<?php


namespace App\Http\Controllers\Web;


class CashTerminalController extends BaseController
{

    public function index()
    {
        return view('web.cashier_term.index');
    }

}