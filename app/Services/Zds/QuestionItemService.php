<?php


namespace App\Services\Zds;


use App\Model\Zds\Question;
use App\Model\Zds\QuestionCate;
use App\Model\Zds\QuestionItem;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;


class QuestionItemService extends BaseService
{
    public function store($data)
    {
        $questionItem = QuestionItem::where('title', $data['title'])->where('question_id', $data['question_id'])->first();
        if($questionItem){
            return $this->error('新增失败,此题库中已存在此问题!');
        }
            $questionItem = QuestionItem::create([
                'title' => isset($data['title']) ? $data['title'] : '',
                'uid'=> $data['uid'],
                'unicid' => $data['unicid'],
                'uuid' => Uuid::uuid4()->toString(),
                'question_id' => $data['question_id'],
                'type' => $data['type'],
                'url' => isset($data['url']) ? $data['url'] : '',
                'answer_items' => isset($data['answer_items']) ? $data['answer_items'] : '',
                'answer' => $data['answer'],
                'easy' => $data['easy'],
                'explain_content' => isset($data['explain_content']) ? $data['explain_content'] :'',
                'explain_video' => isset($data['explain_video']) ? $data['explain_video'] : '',
                'explain_audio' => isset($data['explain_audio']) ? $data['explain_audio'] : '',
                'add_time' => time(),
                ]);
        if($questionItem){
            return $this->success('新增成功!');
        } else {
            return $this->error(
                '新增失败'
            );
        }
    }

    public function edit($id, $data)
    {
        $ret = QuestionItem::where('id', $id)->where(function($query)use($data){
            if($data['is_plat_manager'] != self::ISMANAGER){
                $query->where('uid', $data['uid']);
            }
        })->update(  ['title' => isset($data['title']) ? $data['title'] : '',
                'type' => $data['type'],
                'url' => isset($data['url']) ? $data['url'] : '',
                'answer_items' => isset($data['answer_items']) ? $data['answer_items'] : '',
                'answer' => $data['answer'],
                'easy' => $data['easy'],
                'explain_content' => isset($data['explain_content']) ? $data['explain_content'] :'',
                'explain_video' => isset($data['explain_video']) ? $data['explain_video'] : '',
                'explain_audio' => isset($data['explain_audio']) ? $data['explain_audio'] : '']);
        if($ret){
            return $this->success('新增成功!');
        } else {
            return $this->error(
                '新增失败'
            );
        }
    }


    public function destroy($id, $data)
    {
        $num = QuestionItem::where('id', $id)->where(function($query)use($data, $id){
            if($data['is_plat_manager'] != self::ISMANAGER){
                $query->where('uid', $data['uid']);
            }
            is_array($id) ? $query->whereIn('id', $id) : $query->where('id', $id);
        })->delete();
        if($num > 0 ) {
            return $this->success('删除成功!');
        } else {
            return $this->error('删除失败!');
        }
    }

    public function setStatus($id, $data)
    {
        $ret = QuestionItem::where(function($query)use($data, $id){
            if($data['is_plat_manager'] != self::ISMANAGER){
                $query->where('uid', $data['uid']);
            }
            is_array($id) ? $query->whereIn('id', $id) : $query->where('id', $id);
        })->update(['status' => $data['status']]);
        if($ret) {
            return $this->success('修改成功!');
        } else {
            return $this->error('修改失败!');
        }
    }

    public function import($data, $uid, $unicid)
    {
        $insertData = [];

        foreach($data as $index => $item)
        {

            $row = $index+1;
            if(!trim($item[0]))
            {
                return $this->error('第'.$row.'行的板块ID不能为空');
            }
            $questionCate = Question::where('id', trim(intval($item[0])))->where('uid', $uid)->first();
            if(!$questionCate) {
                return $this->error('第'.$row.'行的板块ID不存在');
            }
            if(!trim($item[2]))
            {
                return $this->error('第'.$row.'行类型不能为空');
            }
            if(intval($item[2]) > 1 && !trim($item[3])) {
                return $this->error('第'.$row.'行资源地址不能为空');
            }
            if(!trim($item[5])){
                return $this->error('第'.$row.'行答案不能为空');
            }
            if(!trim($item[6])){
                return $this->error('第'.$row.'行难易度不能为空');
            }
           /* $questionItem = QuestionItem::where('type', trim($item[2]))->where('question_id', trim($item[0]))->where('title', trim($item[1]))->first();
            if($questionItem) {
                return $this->error('第'.$row.'行标题已经存在');
            }*/
            $insertData[] = [
                'title' => $item[1],
                'uid'=> $uid,
                'unicid' => $unicid,
                'uuid' => Uuid::uuid4()->toString(),
                'question_id' => $item[0],
                'type' => $item[2],
                'url' => $item[3],
                'answer_items' => $item['4'],
                'answer' => $item[5],
                'easy' => $item[6],
                'explain_content' => $item[7],
                'explain_video' => $item[8],
                'explain_audio' => $item[9],
                'add_time' => time(),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        //dd($insertData);
        DB::beginTransaction();
        try{
            $num = QuestionItem::insert($insertData);
            DB::commit();
            if($num)
            {
                return $this->success('导入成功!');
            } else {
                return $this->error('导入失败');
            }
        }catch(\Exception $exception)
        {
            DB::rollBack();
            return $this->error('系统错误'.$exception->getMessage());
        }

    }

}