<?php


namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    public $GPC, $request, $prefix = 'web';

    public function __construct(Request $request)
    {
        $user = Auth::guard('web')->user();
        $this->request = $request;
    }

    public function view($page, array $data = [])
    {
        return view($this->prefix.'.'.$page, $data);
    }
}