<?php

namespace App\Http\Resources\Zds;

use Illuminate\Http\Resources\Json\Resource;

class Question extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'question_title' => $this->question_title,
            'question_cate_id' => $this->question_cate_id,
            'question_cate_title' => $this->questionCate->title,
            'pic' => $this->pic,
            'pass_num' => $this->pass_num,
            'gold_num' => $this->gold_num,
            'reward_money' => $this->reward_money,
            'answer_time' => $this->answer_time,
            'share_info' => json_decode($this->share_info),
            'pass_rewards' => $this->questionRewards,
            'question_item_num' => $this->questionitems->count(),
            'status' => $this->status,
            'is_hot' => $this->is_hot,
            'xn_answer_num' => $this->xn_answer_num,
            'sort' => $this->sort,
            'pass_type' => $this->pass_type,
            'reward_money_type' =>1,
            'created_at' => isset($this->created_at) ? $this->created_at->toDateString() : '',
            'is_mange' => $this->is_mange,
        ];
    }
}
