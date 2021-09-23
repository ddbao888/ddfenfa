<?php


namespace App\Http\Controllers\Api\Zds;


use App\Http\Controllers\Api\Transformer\Zds\QuestionRewardTransformer;
use App\Http\Controllers\Api\Transformer\Zds\QuestionTransformer;
use App\Http\Resources\Zds\Good;
use App\Jobs\PushNoticeJob;
use App\Model\Zds\MemberAnswerCount;
use App\Model\Zds\MemberAnswerlog;
use App\Model\Zds\MemberCredit;
use App\Model\Zds\MemberQuestionCount;
use App\Model\Zds\MemberRewardRecord;
use App\Model\Zds\Question;
use App\Model\Zds\QuestionItem;
use App\Model\Zds\RoomUser;
use App\Repository\Zds\QuestionRep;
use App\Services\ChatService;
use App\Services\Zds\MemberAnswerCountService;
use App\Services\Zds\MemberAnswerlogService;
use App\Services\Zds\MemberService;
use App\Services\Zds\QuestionLikeLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends BaseController
{
    public function list(Request $request)
    {
        $collection = QuestionRep::paginate();
        return $this->response()->paginator($collection, new QuestionTransformer);
    }

    public function first(Request $request)
    {
        $uuid = $request->get('id');
        $question = QuestionRep::first($uuid);
        //获取用户答题数量
        $user = auth('api')->user();
        if(!$question) {
            return $this->response()->error("此题库不存在!");
        }
        return $this->response()->item($question, new QuestionTransformer($user));
    }

    public function postLike(Request $request)
    {
        $uuid = $request->get('id');
        $question = QuestionRep::first($uuid);
        if(!$question) {
            return $this->response()->error("此题库不存在!");
        }
        $user = auth('api')->user();
        QuestionLikeLogService::like($user->id, $question->id);
        return $this->success('成功!');

    }

    public function postAnswer(Request $request)
    {
        sleep(2);
        $itemId = $request->get('id');
        $answer = $request->get('answer');
        $user = auth('api')->user();
        $questionItem = QuestionItem::where('uuid', $itemId)->first();
        $question = $questionItem->question;
        $credit = 0.00;
        $isReward = false;
        if(!$answer) {
            return $this->error('提交失败!订单错误!');
        }
        $memberAnserLog = MemberAnswerlog::where('uid', $user->id)->where('question_item_id', $questionItem->id)->where('status', 0)->first();
        if(!$memberAnserLog)
        {
            return $this->error('提交失败!订单不存在!');
        }
        if($memberAnserLog->addtime - time() > 60)
        {
            $memberAnserLog->status = -1;
            $memberAnserLog->save();
            return $this->error('提交失败!回答超时!');
        }
        //记录答题次数
        MemberAnswerCountService::recordNum($user->id, $question->id);
        $msg = '';
        if(trim($questionItem->answer) == $answer){
            //看是不是随机红包

            if($question->reward_money == 0) {
                $credit = rand(10,50)/100;
            } else {
                $credit = $question->reward_money;
            }

           // $num = MemberCredit::where('uid', $user->id)->where('origin_id', $itemId)->where('origin_type', 'question_item')->count();

                DB::beginTransaction();
                try{
                    //用户答题记录
                   MemberAnswerlogService::setStatus($user->id, $question->id, 1, 1);

                    //加红包
                    MemberService::addCredit($user, $credit, $questionItem->id, 'question_item');
                    /*记录答题正确次数*/
                    MemberAnswerCountService::recordSuccessNum($user->id, $question->id);
                    /*判断用户是否获奖*/
                    $memberQuestionCount = MemberAnswerCount::where('uid', $user->id)->where('question_id', $question->id)->first();

                    /*用户答题数+1*/
                    MemberService::addAnswerSuccessNum($user->id);

                    $pass_num = $memberQuestionCount->success_num;
                    $questionReward = $question->questionRewards()->where('pass_num', $pass_num)->first();
                    if($questionReward)
                    {
                        //发放获奖记录
                        $memberRewardRecord = MemberRewardRecord::where('uid', $user->id)->where('origin_id', $questionReward->id)->where('origin_type', 'question_reward')->first();


                        if(!$memberRewardRecord) { //如果以往没有获奖，进行加入用户获奖记录中
                            $good = $questionReward->good;

                            MemberService::addReward($user, $questionReward->id, 'question_reward', $good);

                            $isReward = true;

                            //PushNoticeJob::dispatch( $user, $question, $good);
                        }
                    }
                    $status = 'success';
                    DB::commit();
                }catch(\Exception $e)
                {
                    DB::rollBack();
                    $msg = $e->getMessage();
                    $status = 'error';
                }

        } else {
            if($question->pass_type == 2) { //连续答题错误，清零
                $memberQuestionCount = MemberAnswerCount::where('uid', $user->id)->where('question_id', $question->id)->first();
                $memberQuestionCount->success_num = 0;
                $memberQuestionCount->save();
            }
            MemberAnswerlogService::setStatus($user->id, $question->id, 1);
            $status = 'error';
        }
        return $this->response()->array(['status' => $status, 'reward_money' => $credit, 'is_reward' => $isReward, 'msg' => $msg]);
    }

    public function roomInfo(Request $request)
    {
        $roomId = $request->get('id');
        $user = auth('api')->user();
        $userRoom = RoomUser::where('uid', $user->id)->where('roomid', $roomId)->first();
        if(!$userRoom) {
            $userRoom = new RoomUser();
            $userRoom->uid = $user->id;
            $userRoom->avatar = $user->avatar;
            $userRoom->nick_name = $user->nick_name;
            $userRoom->roomid =$roomId;
            $userRoom->addtime = time();
            $userRoom->save();
        } else {
            $userRoom->addtime = time();
            $userRoom->save();
        }
        $roomUser = RoomUser::where('roomid', $roomId);

        $onlineNum = $roomUser->count();
        $users = $roomUser->take(5)->select('avatar')->get();
        $me = auth('api')->user();
        return $this->response()->array(['onlineNum' => $onlineNum, 'users' => $users, 'me' =>$me]);
    }


}
