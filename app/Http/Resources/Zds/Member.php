<?php

namespace App\Http\Resources\Zds;

use Illuminate\Http\Resources\Json\Resource;

class Member extends Resource
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
            'nick_name' => $this->nick_name,
            'level_id' => $this->level_id,
            'level_name' => isset($this->level) ? $this->level->level_name : '无',
            'avatar' => $this->avatar,
            'sex' => $this->sex,
            'gold' => $this->gold,
            'credit' => $this->credit,
            'answer_count' => $this->answerlogs->count(),
            'created_at' => $this->created_at->toDateTimeString()
        ];
    }
}
