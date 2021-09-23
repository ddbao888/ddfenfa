<?php


namespace App\Http\Resources\Zds;


use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user_name,
            'is_admin' => $this->is_admin,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString()
        ];
    }
}