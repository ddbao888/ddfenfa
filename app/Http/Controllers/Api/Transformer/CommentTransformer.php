<?php


namespace App\Http\Controllers\Api\Transformer;


use App\Model\Comment;
use App\Model\CommentLikeLog;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    protected $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

    public function transform(Comment $comment)
    {
        return [
            'reply_content' => $comment->reply_content,
            'nick_name' => $comment->nick_name,
            'avatar' => $comment->avatar,
            'reply_time' => $comment->created_at->toDateTimeString(),
            'like_num' => $comment->like_num,
            'id' => $comment->id,
            'is_like' => $this->isLike($comment->id),
            'replyList' => $this->getReplyList($comment),
            'replyText' => '回复'
        ];
    }

    public function isLike($id)
    {
        $commentLikeLog = CommentLikeLog::where('comment_id', $id)->where('uid', $this->user->id)->first();
        return $commentLikeLog ? true : false;
    }

    private function getReplyList($comment)
    {
        $replyList = [];
        $comments = $comment->replyList;
        foreach($comments as $comment)
        {
            $replyList[] = [
                'reply_content' => $comment->reply_content,
                'nick_name' => $comment->nick_name,
                'avatar' => $comment->avatar,
                'reply_time' => $comment->created_at->toDateTimeString(),
                'like_num' => $comment->like_num,
                'id' => $comment->id,
            ];
        }
        return $replyList;
    }
}