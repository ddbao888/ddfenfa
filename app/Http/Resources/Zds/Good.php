<?php

namespace App\Http\Resources\Zds;

use Illuminate\Http\Resources\Json\Resource;

class Good extends Resource
{

    const REDBAG = 1;
    const ENTITY = 3;
    const VIRTUAL = 2;
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
            'good_name' => $this->good_name,
            'type' => $this->type,
            'type_name' => $this->type_name,
            'good_price' => $this->good_price,
            'good_price2' => $this->good_price2,
            'pic' => json_decode($this->pic),
            'content' => $this->content,
            'user_name' => $this->user->real_name,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString()
        ];
    }
}
