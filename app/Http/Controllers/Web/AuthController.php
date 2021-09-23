<?php


namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    protected  $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function postLogin(Request $request)
    {
        $data = $request->all();
        return $this->authService->login($data);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/fenfa/login');
    }

    public function account()
    {
        return view("web.password");
    }

    public function updateAccount(Request $request)
    {
        $oldPassword = $request->get("oldPassword");
        $newPassword = $request->get("newPassword");
        $user = Auth::guard("web")->user();
        if(Hash::check($oldPassword, $user->password)){
            $user->password = Hash::make($newPassword);
            $ret = $user->save();
            if($ret) {
                return response()->json(["status" =>"success", "msg" => "密码修改成功"]);

            } else {
                return response()->json(["status" =>"error", "msg"=> "密码修改失败"]);
            }
        } else {
            return response()->json(["status" =>"error", "msg"=> "原始密码错误"]);
        }
    }


}