<?php


namespace App\Services;


use App\Model\QuestionFeedBack;

class QuestionFeedService extends BaseService
{
    public function add($uid, $data)
    {
        $questionFeed = new QuestionFeedBack();
        $questionFeed->uid = $uid;
        $questionFeed->title = $data['title'];
        $questionFeed->pic = isset($data['pic']) ? $data['pic'] : '';
        $questionFeed->type = $data['type'];
        $ret = $questionFeed->save();

        if($ret){
            return $this->success('提交成功!');
        } else {
            return $this->error('提交失败!');
        }


    }


}