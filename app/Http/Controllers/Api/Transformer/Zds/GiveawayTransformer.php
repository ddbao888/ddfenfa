<?php


namespace App\Http\Controllers\Api\Transformer\Zds;


use App\Model\Zds\Order;
use League\Fractal\TransformerAbstract;

class GiveawayTransformer extends TransformerAbstract
{
    public function transform(Order $order)
    {
        return [
            'real_name' => $order->real_name,
            'giveaway' => $order->giveway,

            'created_at' => $order->created_at->toDateTimeString()
        ];
    }
}