<?php


namespace App\Services;


use App\Model\MgUser;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function add($data)
    {
        $user = MgUser::where('user_name', $data['user_name'])->first();
        if($user){
            return $this->error('用户名已经存在');
        }
        $user = new MgUser();
        $user->user_name = $data['user_name'];
        $user->password = Hash::make($data['password']);
        $user->is_admin = 0;
        $user->status = 1;
        $user->is_plat_manager = 0;
        $ret = $user->save();
        if($ret) {
            return $this->success('保存成功!');
        } else {
            return $this->error('保存失败!');
        }
    }

    public function update($id, $data)
    {
        $user = MgUser::where('id', $id)->first();
        $user->password = Hash::make($data['password']);
        $ret = $user->save();
        if($ret) {
            return $this->success('保存成功!');
        } else {
            return $this->error('保存失败!');
        }
    }

    public function setStatus($id)
    {
        $user = MgUser::where('id', $id)->first();
        $user->status = 0;
        $ret = $user->save();
        if($ret) {
            return $this->success('保存成功!');
        } else {
            return $this->error('保存失败!');
        }
    }
}