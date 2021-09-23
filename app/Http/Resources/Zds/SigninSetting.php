<?php

namespace App\Http\Resources\Zds;

use Illuminate\Http\Resources\Json\Resource;

class SigninSetting extends Resource
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
            'day' => $this->day,
            'reward_type' => $this->reward_type,
            'gold' => $this->gold,
            'is_show' => $this->is_show,
            'good_id' => $this->good_id,
            'good_name' => isset($this->good->good_name) ? $this->good->good_name : '',
        ];
    }
}
