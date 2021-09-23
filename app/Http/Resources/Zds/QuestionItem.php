<?php

namespace App\Http\Resources\Zds;

use Illuminate\Http\Resources\Json\Resource;

class QuestionItem extends Resource
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
            'unicid'=> $this->unicid,
            'question_title' => $this->question->question_title,
            'title' => $this->title,
            'type' => $this->type,
            'url' => $this->url,
            'answer_items' => $this->answer_items,
            'answer' => $this->answer,
            'explain_content' => $this->explain_content,
            'explain_audio' => $this->explain_audio,
            'explain_video' => $this->explain_video,
            'created_at' => isset($this->created_at) ? $this->created_at->toDateTimeString() : '',
            'status' => $this->status,
            'name' => $this->user->real_name,
            'view' => $this->view,
            'easy' => $this->easy
        ];
    }
}
