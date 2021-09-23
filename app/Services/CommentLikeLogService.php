<?php


namespace App\Services;


use App\Model\Comment;
use App\Model\CommentLikeLog;

class CommentLikeLogService extends BaseService
{
    public function like($uid, $commentId)
    {
        $commentLikeLog = CommentLikeLog::where('uid', $uid)->where('comment_id', $commentId)->first();
        $comment = Comment::where('id', $commentId)->first();
        if($commentLikeLog)
        {
            CommentLikeLog::where('uid', $uid)->where('comment_id', $commentId)->delete();

            $comment->like_num = $comment->like_num -1;
            $comment->save();
        } else {
            $commentLikeLog = new CommentLikeLog();
            $commentLikeLog->uid = $uid;
            $commentLikeLog->comment_id = $commentId;
            $commentLikeLog->save();

            $comment->like_num = $comment->like_num +1;
            $comment->save();

        }
        return $this->success('操作成功');
    }


}