<?php



namespace App\Http\Controllers\Api\Zds;

use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CaptController extends BaseController
{
    //生成图片与验证码
    public function captchaShow()
    {

        // 设置背景颜色
        $builder = new CaptchaBuilder();

        // 设置背景颜色
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        //可以设置图片宽高及字体
        $builder->build($width = 100, $height = 50, $font = null);
        //获取验证码的内容,并转化为小写
        $phrase = strtolower($builder->getPhrase());
        //把内容存入session
//        session(['phrase' => $phrase]);
        Session::put('phrase', $phrase);
        //生成图片
        header('Content-Type: image/jpeg');
        $builder->output();
    }

    public function checkCapt(Request $request)
    {
        $data = $request->except('_token');
//        dd(Session::get('phrase'))  ;
//        die();
        if (strtolower(Session::get('phrase')) ==strtolower( $data['captcha'])) {
            Session::forget('phrase');
            return '验证码正确';
        } else {
            //用户输入验证码错误
            return '验证码输入错误';
        }

    }
}
