<?php


namespace App\Services\Zds;


use App\Model\Zds\Question;
use App\Model\Zds\QuestionCate;

class QuestionCateService extends BaseService
{
    public function store($data)
    {
        $questionCate = QuestionCate::where('uid', $data['uid'])->where('unicid', $data['unicid'])->where('title', trim($data['title']))->first();
        if($questionCate){
            return $this->error('分类名称已经存在!');
        }
        $questionCate = new QuestionCate();
        $questionCate->title = $data['title'];
        $questionCate->pic = $data['pic'];
        $questionCate->uid = $data['uid'];
        $questionCate->unicid = $data['unicid'];
        $questionCate->status = $data['status'];
        $ret = $questionCate->save();
        if($ret)
        {
            return $this->success('保存成功!');
        } else {
            return $this->error('保存失败!');
        }
    }

    public function edit($data)
    {
        $questionCate = QuestionCate::where('id', $data['id'])->first();
        $questionCate->title = $data['title'];
        $questionCate->pic = $data['pic'];
        $questionCate->status = $data['status'];
        $ret = $questionCate->save();
        if($ret)
        {
            return $this->success('保存成功!');
        } else {
            return $this->error('保存失败!');
        }
    }

    public function destroy($id)
    {
        $question = Question::where('question_cate_id', $id)->first();
        if($question)
        {
            return $this->error('问题库中存在此类，禁止删除!');
        }
        $num = QuestionCate::where(function($query)use($id){
            is_array($id) ? $query->whereIn('id', $id) : $query->where('id', $id);
        })->delete();
        if($num > 0 )
        {
            return $this->success('删除成功!');
        } else {
            return $this->error('删除失败!');
        }
    }
}