<?php


namespace App\Http\Controllers\Api\Transformer\Zds;


use App\Model\Zds\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    public function transform(Order $order)
    {
        return [
            'real_name' => $order->real_name,
            'giveaway' => $order->giveway,
            'unit' => $order->unit,
            'num' => $order->num,
            'pic' => 'https://bao-1253429312.cos.ap-nanjing.myqcloud.com/zds/image/2021/7/23/1627006500172908147.png',
            'good_name' => $order->good_name,
            'express_no' => $order->express_no,
            'express_name' => $order->express_name,
            'good_price' => $order->good_price,
            'order_price' => $order->order_price,
            'out_trade_no' => $order->out_trade_no,
            'created_at' => $order->created_at->toDateTimeString()
        ];
    }
}