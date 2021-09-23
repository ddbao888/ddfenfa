<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MemberWithdraw extends Resource
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
            'nick_name' => isset($this->member->nick_name) ? $this->member->nick_name : '',
            'avatar' => isset($this->member->avatar) ? $this->member->avatar : '',
            'status' => $this->status,
            'credit' => $this->credit,
            'created_at' => $this->created_at->toDateTimeString()

        ];
    }
}
