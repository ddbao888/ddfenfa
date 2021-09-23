<?php


namespace App\Http\Controllers\Api\Zds;


use App\Http\Controllers\Api\Transformer\Zds\MemberAnswerCountTransformer;
use App\Http\Controllers\Api\Transformer\Zds\MemberRewardRecordTransformer;
use App\Http\Controllers\Api\Transformer\Zds\MemberTransformer;
use App\Http\Resources\Zds\Good;
use App\Model\Zds\Member;
use App\Model\Zds\MemberAnswerCount;
use App\Model\Zds\MemberSignin;
use App\Model\Zds\Question;
use App\Model\Zds\Setting;
use App\Model\Zds\SignSetting;
use App\Services\Zds\MemberService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends BaseController
{


    public function questionMyInfo(Request $request)
    {
        $user  = auth('api')->user();
        $uuid = $request->get('uuid');
        $question = Question::where('uuid', $uuid)->first();
        $answersNum = 0;
        $answersSuccessNum = 0;
        $memberAnswerCount = MemberAnswerCount::where('uid', $user->id)->where('question_id', $question->id)->first();
        if($memberAnswerCount) {
            $answersNum = $memberAnswerCount->num;
            $answersSuccessNum = $memberAnswerCount->success_num;
        }

        //计算总的答题用户
        $total = MemberAnswerCount::where('question_id', $question->id)->count();
        $fm = MemberAnswerCount::where('question_id', $question->id)->where('num', '<', $answersNum)->count();
        $rate = $fm == 0 ? 0 : ($total/$fm)*100;

        return response()->json([
            'answerSuccessNum' => $answersSuccessNum,
            'nick_name' => $user->nick_name,
            'avatar' => $user->avatar,
            'gold' => $user->gold,
            'credit' => $user->credit,
            'pk_rate' => $rate,
            'rewards' => $user->rewards()->select('origin_id')->where('origin_type','question_reward')->get()
        ]);
    }


    public function signInStore(Request $request)
    {
        $user  = auth('api')->user();
        $memberSign = MemberSignin::where('uid', $user->id)->whereDate('created_at', Carbon::now()->format('Y-m-d'))->orderBy('id', 'desc')->first();
     /*   $xgday = -1;
        if($memberSign) {
            $xgday = Carbon::now()->diffInDays($memberSign->created_at);
        }
        dd($xgday);*/
        if($memberSign)
        {
            return $this->error('今日已签，明天再来！');
        }

        $day = MemberSignin::where('uid', $user->id)->orderBy('id', 'desc')->count();
        $signSetting = SignSetting::where('unicid', 1)->where('day', $day+1)->first();
        $memberSign = MemberSignin::where('uid', $user->id)->where('signin_id', $signSetting->id)->first();

        if($signSetting) {
            $memberSign = new MemberSignin();
            $memberSign->uid = $user->id;
            $memberSign->signin_id = $signSetting->id;
            $memberSign->unicid = 1;
            $memberSign->save();
            
            //金币
            if($signSetting->reward_type == 1) {
                MemberService::addGold($user, $signSetting->gold, $signSetting->id, 'signin');
            } 
            if($signSetting->reward_type == 2) {
                $good = $signSetting->good;
                if($good) {
                    MemberService::addReward($user,  $signSetting->id,'signin', $good );
                    if($good->type == \App\Model\Zds\Good::REDBAG)
                    {
                        MemberService::addCredit($user, $good->money, $signSetting->id, 'signin');
                    }
                } else {
                    return $this->error('签到未设置奖励物品!');
                }
            }
            MemberService::addSigninLog($user->id, $signSetting->id);
            return $this->success('签到成功,', [
                'reward_type' => $signSetting->reward_type,
                'gold' => $signSetting->gold, 'good_name' => isset($signSetting->good->good_name) ? $signSetting->good->good_name: '']);
        } else {
            return $this->error('签到奖励未设置!');
        }

    }

    public function signins(Request $request)
    {
        $user  = auth('api')->user();

        $signs = SignSetting::all();
        $memberSign = MemberSignin::where('uid', $user->id)->orderBy('id', 'desc');
        $total = $memberSign->count();
        $memberSign = $memberSign->first();
        $signsArr = [];
        foreach($signs as $sign)
        {
            $signsArr[] = ['day' => $sign->day,
                'reward_type' => $sign->reward_type,
                'gold' => $sign->gold,
                'good_name' => isset($sign->good->good_name) ? $sign->good->good_name : '',
                'is_show' => $sign->is_show,
                'is_select' => false,
            ];
        }
        if($memberSign) {
            $xgday = Carbon::now()->diffInDays($memberSign->created_at);

            //
                $memberSigns = DB::table('signin_settings')->leftJoin('member_signins', function($join)use($user){
                    $join->on('signin_settings.id', '=', 'member_signins.signin_id')->where('member_signins.uid', $user->id);
                })->select("signin_settings.*","member_signins.signin_id")->orderBy('signin_settings.id')->get();
                $signsArr = [];
                foreach($memberSigns as $item)
                {
                    $signsArr[] = ['day' => $item->day,
                        'reward_type' => $item->reward_type,
                        'gold' => $item->gold,
                        'good_name' => isset($item->good->good_name) ? $item->good->good_name : '',
                        'is_show' => $item->is_show,
                        'is_select' => $item->signin_id ? true : false,
                    ];
                }

        }
        return response()->json(['data' => $signsArr, 'qd_day' => $total]);
    }

    public function task()
    {
        $user = auth('api')->user();
        $signinsCount = MemberSignin::where('uid', $user->id)->count();
        $inviteCount = Member::where('uid', $user->id)->count();
        $setting = Setting::where('unicid', 1)->first();

        return $this->response()->array(['signinCount'=> $signinsCount,'gold' => $user->gold, 'inviteCount' => $inviteCount, 'share' => $setting->wx_share]);
    }

    public function me()
    {
        $user = auth('api')->user();
        return $this->response()->item($user, new MemberTransformer);
    }

    //答题记录
    public function answerlog()
    {
        $user = auth('api')->user();
        $memberAnswerlog = MemberAnswerCount::where('uid', $user->id)->get();
        return $this->response()->collection($memberAnswerlog, new MemberAnswerCountTransformer);
    }

    //我的奖励
    public function reward(Request $request)
    {
        $status = $request->get('status', 0);
        $user = auth('api')->user();
        $rewards = $user->rewards()->orderBy('id', 'desc')->where(function($query)use($status) {
            if(!empty($status)) {
                $query->where('status', $status);
            }
        })->get();
        return $this->response()->collection($rewards, new MemberRewardRecordTransformer);
    }
}
