<?php


namespace App\Http\Controllers\Api\Zds;


use App\Http\Controllers\Api\Transformer\Zds\QuestionFeedTransformer;
use App\Model\QuestionFeedBack;
use App\Services\QuestionFeedService;
use Illuminate\Http\Request;

class QuestionFeedController extends BaseController
{
    public function store(Request $request)
    {
        $title = $request->get('title');
        $pic = $request->get('pic');

        $data['title'] = $title;
        $data['pic'] = $pic;
        $data['type'] = 'zds';
        $user = auth('api')->user();
        $service = new QuestionFeedService();
        return $service->add($user->id, $data);
    }

    public function list()
    {
        $user = auth('api')->user();
        $data = QuestionFeedBack::where('uid', $user->id)->orderBy('id', 'desc')->paginate(15);
        return $this->response()->paginator($data, new QuestionFeedTransformer);
    }
}