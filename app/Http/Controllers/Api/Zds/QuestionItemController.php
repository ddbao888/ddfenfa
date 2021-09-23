<?php


namespace App\Http\Controllers\Api\Zds;


use App\Http\Controllers\Api\Transformer\Zds\QuestionItemTransformer;
use App\Model\Zds\Question;
use App\Model\Zds\QuestionItem;
use App\Services\Zds\MemberAnswerlogService;
use App\Services\Zds\MemberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionItemController extends BaseController
{
    public function index(Request $request)
    {
        $uuid = $request->get('questionId');
        $user = auth('api')->user();
        $question = Question::where('uuid', $uuid)->first();
        $total = $question->questionItems()->where('status', 2)->count()-1;
        $skip = mt_rand(0, $total);
        $questionItem = $question->questionItems()->where('status', 2)->skip($skip)->take(1)->first();
        if($user->gold < $question->gold_num)
        {
            return $this->error('金币不足，可通过任务获取金币!');
        }

        MemberAnswerlogService::add($user->id, $questionItem->id);

            $user->answer_num += 1;
            $user->save();
            //增加用户答题数量
        //MemberService::addAnswerNum($user->id);

        MemberService::addGold($user, -$question->gold_num, $questionItem->id, 'question_item');




        return $this->response()->item($questionItem, new QuestionItemTransformer);
    }
}
