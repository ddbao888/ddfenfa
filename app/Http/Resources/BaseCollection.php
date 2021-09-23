<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
    public function with($request)
    {
        return [

            'total' => $this->total(),
            'meta' => [
                'key' => 'value',
            ]
        ];
    }
}