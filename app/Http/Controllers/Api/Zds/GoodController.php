<?php


namespace App\Http\Controllers\Api\Zds;


use App\Http\Controllers\Api\Transformer\Zds\GoodTransformer;
use App\Model\Zds\Good;
use App\Model\Zds\Order;
use App\Model\Zds\Setting;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class GoodController extends BaseController
{



    public function basic()
    {
        $setting = Setting::where('unicid', 2)->first();
        return $this->response()->array(['desc' => $setting->desc, 'buy_limit' => $setting->buy_limit, 'wx_share' => $setting->wx_share]);
    }

    public function index(Request $request)
    {
        $good = Good::where('id', 8)->first();

        return $this->response()->item($good, new GoodTransformer);
    }

    public function createOrder(Request $request)
    {
        $unit = $request->get('unit');
        $setting = Setting::where('id', 2)->first();
        $goodPrice = $request->get('good_price');
        $num = $request->get('num');
        $uuid = Uuid::uuid4()->toString();
        $goodName = $request->get('good_name');
        $order = new Order();
        $order->num = $num;
        $order->unit = $unit;
        $order->good_price = $goodPrice;
        $order->giveaway = ($goodPrice * $num)*(($setting->scales)/100);
        $order->good_name = $goodName;
        $order->uuid = $uuid;
        $order->status = 0;
        $ret = $order->save();
        if($ret){
            return $this->response()->array(['status' => 'success', 'uuid' => $uuid]);
        } else {
            return $this->response()->array(['status' => 'error', 'uuid' => $uuid]);
        }
    }

    public function getOrder(Request $request)
    {
        $uuid = $request->get('uuid');
        $order = Order::where('uuid', $uuid)->first();
        return $this->response()->array(['status' => 'success',
            'good_name' => $order->good_name,
            'good_price' => $order->good_price,
            'unit' => $order->unit,
            'num' => $order->num,
            'giveaway' => $order->giveaway,
            'money' => $order->good_price * $order->num,
            ]);
    }

    public function myOrder(Request $request)
    {
        $openid = $request->get('openid');
        $orders = Order::where('openid', $openid)->get();
        return $this->response->collection($orders);
    }

    public function createPay(Request $request)
    {
        $uuid = $request->get('uuid');
        $order = Order::where('uuid', $uuid)->first();

    }

}