<?php


namespace App\Http\Controllers\Api\Transformer\Zds;


use App\Model\QuestionFeedBack;
use League\Fractal\TransformerAbstract;

class QuestionFeedTransformer extends TransformerAbstract
{
    public function transform(QuestionFeedBack $feedBack)
    {
        return [
            'title' => $feedBack->title,
            'pic' => $feedBack->pic,
            'status' => $feedBack->status,
            'status_name' => $feedBack->status_name,
            'created_at' => $feedBack->created_at->toDateTimeString()
        ];
    }
}