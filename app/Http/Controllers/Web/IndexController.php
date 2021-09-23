<?php


namespace App\Http\Controllers\Web;


use App\Model\MgMenu;
use Illuminate\Http\Request;

class IndexController extends BaseController
{

    public function dashboard()
    {
        return view('web.dashboard.index');
    }

    public function index()
    {

        $menus = MgMenu::where('parent_id', 0)->orderBy('id')->get();
        return view('web.index', ['menus' => $menus]);
    }
}