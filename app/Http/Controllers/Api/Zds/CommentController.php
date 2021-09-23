<?php


namespace App\Http\Controllers\Api\Zds;


use App\Http\Controllers\Api\Transformer\CommentTransformer;
use App\Http\Controllers\Api\Zds\BaseController;
use App\Model\Comment;
use App\Model\CommentLikeLog;
use App\Model\Zds\Question;
use App\Services\CommentLikeLogService;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    public function store(Request $request)
    {
        $user = auth('api')->user();
        $unicid = 1;
        $data = $request->all();
        $comment = new CommentService();
        return $comment->add($user, $unicid, $data);
    }

    public function list(Request $request)
    {
        $originId = $request->get('originId');
        $originType = $request->get('originType');
        $user = auth('api')->user();
        $question = Question::where('uuid', $originId)->where('status', 2)->firstOrFail();
        $comments = Comment::where('origin_id', $question->id)->where('origin_type', $originType)->where('status', Comment::PASS)->orderBy('id')->paginate(20);
        return $this->response()->paginator($comments, new CommentTransformer($user));
    }

    public function like(Request $request)
    {
        $user = auth('api')->user();
        $id = $request->get('id');
        $service = new CommentLikeLogService();
        return $service->like($user->id, $id);

    }
}