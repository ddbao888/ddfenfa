<?php


namespace App\Http\Controllers\Web;


use App\Model\MgMenu;

class HomeController extends BaseController
{
    public function index()
    {
        $menus = MgMenu::where('parent_id', 0)->orderBy('sort')->get();


        return $this->view('index', ['menus' => $menus]);
    }

    public function dashboard()
    {
    
        return $this->view('dashboard');
    }
}