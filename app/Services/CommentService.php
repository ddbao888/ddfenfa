<?php


namespace App\Services;


use App\Model\Comment;
use App\Model\Zds\Question;

class CommentService extends BaseService
{
    public function add($user, $unicid, $data)
    {
        $this->rules = ['replyContent' => 'required|max:500','originId' => 'required', 'originType' => 'required'];
        $this->messages = [
            'replyContent.required' => '回复内容不能为空',
            'replyContent.max' => '内容长度不能超过500',
            'originId.required' => '不能为空',
            'originType.required' => 'originType不能为空'
        ];
        $this->dataValidator($data);
        try{
            $question = Question::where('uuid', $data['originId'])->first();
            $comment = new Comment();
            $comment->uid = $user->id;
            $comment->unicid = $unicid;
            $comment->reply_content = $data['replyContent'];
            $comment->nick_name = $user->nick_name;
            $comment->avatar = $user->avatar;
            $comment->origin_id = $question->id;
            $comment->origin_type = $data['originType'];
            $comment->parent_id = $data['parentId'];
            $comment->reply_time = time();
            $ret = $comment->save();
            if($ret){
                return $this->success('评论成功!');
            } else {
                return $this->error('评论失败!');
            }
        }catch(\Exception $e)
        {
            return $this->error('系统错误'.$e->getMessage());
        }

    }
}