<?php


namespace App\Http\Controllers\Api\Zds;


use App\Model\Zds\Member;
use Illuminate\Http\Request;
use Iwanli\Wxxcx\Wxxcx;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

class WxxcxController extends BaseController
{
    protected $wxxcx;
    public function __construct(Wxxcx $wxxcx)
    {
        $this->wxxcx = $wxxcx;
    }

    public function login(Request $request)
    {
        $code = $request->get('code');
        $iv = $request->get('iv');
        $uid = $request->get('uid', 0);
        $rawData = $request->get('rawData');
        $rawData = json_decode($rawData, true);
        $encryptedData = $request->get('encryptedData');
        //encryptedData 和 iv 在小程序端使用 wx.getUserInfo 获取
        $userInfo = $this->getWxUserInfo($code, $encryptedData, $iv);

        $userArr = json_decode($userInfo, true);

        if(isset($userArr['code']) &&  $userArr['code']== '10001')
        {
            return $this->error($userArr['message']);
        }
        $member = Member::where('wx_openid', $userArr['openId'])->first();
        if(!$member) {
            $member = new Member();
            $member->wx_openid = $userArr['openId'];
            $member->nick_name = $rawData['nickName'];
            $member->sex = $rawData['gender'];
            $member->unicid = 1;
            $member->uid = $uid;
            $member->addtime = time();
            $member->avatar = $rawData['avatarUrl'];
            $member->gold = 10;
            $member->save();
            $member = Member::where('wx_openid', $userArr['openId'])->first();
        }
        $token = JWTAuth::fromUser($member);
        $member->token = $token;
        $member->save();
        return $this->success('成功', ['token' => $token]);
    }

    public function getWxUserInfo($code, $encryptedData, $iv)
    {
        //code 在小程序端使用 wx.login 获
        //根据 code 获取用户 session_key 等信息, 返回用户openid 和 session_key
        $userInfo = $this->wxxcx->getLoginInfo($code);
        //获取解密后的用户信息
        return $this->wxxcx->getUserInfo($encryptedData, $iv);
    }
}
