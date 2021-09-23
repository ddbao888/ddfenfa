<?php


namespace App\Services;


use App\Model\MgUser;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService extends BaseService
{
    public function login($data)
    {
        $this->rules = ['user_name' => 'required', 'password' => 'required'];
        $this->messages = ['user_name.required' => '请输入用户名', 'password.required' => '密码不能为空!'];
        $this->dataValidator($data);
        $user = MgUser::where('user_name', $data['user_name'])->first();
        if(!$user) {
            return errorMessage('用户不存在,请检查用户名!');
        }
        if(!$user->status) {
            return errorMessage('用户为禁用状态!');
        }
        if(Hash::check($data['password'], $user->password)) {
            Auth::guard('web')->login($user);
            return successMessage('登录成功!');
        } else {
            return errorMessage('密码错误!');
        }
    }
}