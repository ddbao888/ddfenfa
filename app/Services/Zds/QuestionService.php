<?php


namespace App\Services\Zds;

use App\Model\Zds\Question;
use App\Model\Zds\QuestionItem;
use App\Model\Zds\QuestionReward;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class QuestionService extends BaseService
{
    public function store($data)
    {
        $question = Question::where('question_title', $data['question_title'])->where('unicid', $data['unicid'])->first();
        if($question)
        {
            return $this->error('题库名称已经存在!');
        }
        DB::beginTransaction();
        try {
            $questionId = Question::insertGetId([
                'uuid' => Uuid::uuid4()->toString(),
                'question_title' =>$data['question_title'],
                'pic' => $data['pic'],
                'pass_num' => $data['pass_num'],
                'gold_num' => $data['gold_num'],
                'reward_money' => $data['reward_money'],
                'question_cate_id' => $data['question_cate_id'],
                'share_info' => isset($data['share_info']) && $data['share_info'] ? json_encode($data['share_info']) : json_encode([]),
                'uid' => $data['uid'],
                'unicid' => $data['unicid'],
                'sort' => $data['sort'],
                'is_mange' => isset($data['is_mange']) ? $data['is_mange'] : 0,
                'xn_answer_num' => $data['xn_answer_num'],
                'answer_time' => $data['answer_time'],
                'pass_type' => $data['pass_type'],
                'add_time' => time(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            if(isset($data['pass_rewards']) && $data['pass_rewards']) {
                foreach ($data['pass_rewards'] as $item) {
                    $reward = new QuestionReward();
                    $reward->question_id = $questionId;
                    $reward->pass_num = $item['pass_num'];
                    $reward->good_title = $item['good_title'];
                    $reward->good_id = $item['good_id'];
                    $reward->save();
                }
            }
            DB::commit();
            return $this->success('新增成功!');
        }catch(\Exception $exception){
            DB::rollBack();
            return $this->error('系统错误'.$exception->getMessage());
        }

    }

    public function edit($id, $data)
    {
        $question = Question::where('id', $id)->where('uid', $data['uid'])->first();
        if($question) {
            DB::beginTransaction();
            try{
                $question->question_title = $data['question_title'];
                $question->pic = $data['pic'];
                $question->pass_num = $data['pass_num'];
                $question->gold_num = $data['gold_num'];
                $question->reward_money = $data['reward_money'];
                $question->question_cate_id = $data['question_cate_id'];
                $question->share_info = isset($data['share_info']) && $data['share_info'] ? json_encode($data['share_info']) : json_encode([]);
                $question->xn_answer_num = $data['xn_answer_num'];
                $question->is_mange = isset($data['is_mange']) ? $data['is_mange'] : 0;
                $question->sort = $data['sort'];
                $question->answer_time = $data['answer_time'];
                $question->pass_type = $data['pass_type'];
                $ret = $question->save();
                if(isset($data['pass_rewards']) && $data['pass_rewards']) {
                    $question->questionRewards()->delete();
                    foreach($data['pass_rewards'] as $item)
                    {
                        $reward = new QuestionReward();
                        $reward->question_id = $question->id;
                        $reward->pass_num = $item['pass_num'];
                        $reward->good_title = $item['good_title'];
                        $reward->good_id = $item['good_id'];
                        $reward->save();
                    }
                }

                DB::commit();
                return $this->success('保存成功!');
            }catch(\Exception $exception){
                return $this->error('系统错误'.$exception->getMessage());
            }

        } else {
            return $this->error('题库不存在!');
        }
    }

    public function delete($id, $data)
    {
        $questionItem = QuestionItem::where(function($query)use($id){
            is_array($id) ? $query->whereIn('question_id', $id) : $query->where('question_id', $id);
        })->first();
        if($questionItem) {
            return $this->error('删除失败，题目中含有此题库！');
        }
        try {
            QuestionReward::where('question_id', $id)->delete();
            $num = Question::where(function($query)use($id,$data){
                is_array($id) ? $query->whereIn('id', $id) : $query->where('id', $id);
            })->where('uid', $data['uid'])->where('unicid', $data['unicid'])->delete();
            if($num > 0)
            {
                return $this->success('删除成功!');
            } else {
                return $this->error('删除失败!');
            }
        }catch(\Exception $e){
            return $this->error('删除失败!'.$e->getMessage());
        }

    }

    public function setHot($id, $hot)
    {
        $ret = Question::where(function($query)use($id){

            is_array($id) ? $query->whereIn('id', $id) : $query->where('id', $id);
        })->update(['is_hot' => $hot]);
        if($ret){
            return $this->success('设置成功!');
        } else {
            return $this->error('设置失败!');
        }
    }

    public function setStatus($id, $status)
    {
        if(!$id || !$status){
            return $this->error('操作失败缺少参数!');
        }
        $ret = Question::where(function($query)use($id){
            is_array($id) ? $query->whereIn('id', $id) : $query->where('id', $id);
        })->update(['status' => $status]);
        if($ret){
            return $this->success('设置成功!');
        } else {
            return $this->error('设置失败!');
        }
    }
}