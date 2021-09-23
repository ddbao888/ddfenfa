<?php


namespace App\Http\Controllers\Api\Transformer\Zds;


use App\Model\Zds\Good;
use League\Fractal\TransformerAbstract;

class GoodTransformer extends TransformerAbstract
{
    public function transform(Good $good)
    {
        return [
            'good_name' => $good->good_name,
            'good_price' => $good->good_price,
            'good_price2' => $good->good_price2,
            'content' => $good->content,
            'pic' => json_decode($good->pic),
        ];
    }
}