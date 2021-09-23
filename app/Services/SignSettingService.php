<?php


namespace App\Services;


use App\Model\Zds\MemberSignin;
use App\Model\Zds\SignSetting;

class SignSettingService extends BaseService
{
    public function store($uid, $unicid, $data)
    {
        foreach($data as $item)
        {
            if(isset($item['id']) && $item['id']) {
                $signInSetting = SignSetting::where('id', $item['id'])->where('uid', $uid)->first();
                $signInSetting->day = $item['day'];
                $signInSetting->reward_type = $item['reward_type'];
                $signInSetting->gold = isset($item['gold']) ? $item['gold'] : 0;
                $signInSetting->good_id = isset($item['good_id'])? $item['good_id'] : 0;
                $signInSetting->is_show = isset($item['is_show']) ? $item['is_show'] : 1;
                $ret = $signInSetting->save();
            } else {
                $signInSetting = new SignSetting();
                $signInSetting->uid = $uid;
                $signInSetting->unicid = $unicid;
                $signInSetting->day = $item['day'];
                $signInSetting->reward_type = $item['reward_type'];
                $signInSetting->gold = isset($item['gold']) ? $item['gold'] : 0;
                $signInSetting->good_id = isset($item['good_id'])? $item['good_id'] : 0;
                $signInSetting->is_show = isset($item['is_show']) ? $item['is_show'] : 1;
                $ret = $signInSetting->save();
            }

        }

        if($ret) {
            return successMessage('保存成功!');
        } else {
            return errorMessage('保存失败!');
        }
    }

    public function edit($id, $data)
    {

        if($ret) {
            return successMessage('保存成功!');
        } else {
            return errorMessage('保存失败!');
        }
    }

    public function delete($id, $data)
    {
        $memberSignIn = MemberSignin::where('signin_id', $id)->first();
        if($memberSignIn) {
            return errorMessage('签到记录已经存在，不能删除!');
        }
        $num = SignSetting::where('id', $id)->where('uid', $data['uid'])->delete();
        if($num > 0) {
            return successMessage('删除成功！');
        } else {
            return successMessage('删除失败！');
        }
    }
}