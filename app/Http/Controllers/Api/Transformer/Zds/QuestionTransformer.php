<?php


namespace App\Http\Controllers\Api\Transformer\Zds;


use App\Model\CommentLikeLog;
use App\Model\Zds\Member;
use App\Model\Zds\Question;
use App\Model\Zds\QuestionLikeLog;
use League\Fractal\TransformerAbstract;

class QuestionTransformer extends TransformerAbstract
{

    protected $user;
    public function __construct(Member $user=null)
    {
        $this->user = $user;
    }

    public function transform(Question $question)
    {
        return [
            'id' => $question->uuid,
            'pic' => $question->pic,
            'title' => $question->question_title,
            'is_hot' => $question->is_hot,
            'gold_num' => $question->gold_num,
            'share_info' => $question->share_info,
            'reward_money' => $question->reward_money == 0 ? '随机红包' : $question->reward_money.'元固定红包',
            'zan' => $question->zan,
            'answer_time' => $question->answer_time,
            'is_like' => $this->user ? $this->isLike($question->id) : false,
            'likes' => $question->likes->count(),
            'comment_num' => $question->comments->count(),
            'rewards' => $this->includeQuestionRewards($question),
            'is_mange' => $question->is_mange,
            'description' => '在规定时间内，回答正确可获得'.($question->reward_money == 0 ? '随机红包' : $question->reward_money.'元固定红包').',每次回答将扣除'.$question->gold_num.'个金币，超时按回答错误计算。'
        ];
    }

    public function isLike($id)
    {
        $commentLike = QuestionLikeLog::where('uid', $this->user->id)->where('question_id', $id)->first();
        return $commentLike ? true : false;
    }

    public function includeQuestionRewards(Question $question)
    {
        $rewards = $question->questionRewards;
        $collection = [];
        foreach($rewards as $item)
        {
            $collection[] = ['id'=> $item->id,'pic' => isset($item->good->pic) ? $item->good->pic : '', 'good_name' => isset($item->good->good_name) ? $item->good->good_name : '', 'pass_num' => $item->pass_num];
        }
        return $collection;
    }
}
