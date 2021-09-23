<?php


namespace App\Http\Controllers\Zds;


use App\Http\Resources\Zds\OrderCollection;
use App\Model\Zds\Order;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        return view('zds.order.index', ['status' => $status]);
    }

    public function list(Request $request)
    {
        $status = $request->get('status');
        $orders = Order::where('status', $status)->orderBy('id', 'desc')->paginate(30);
        return new OrderCollection($orders);
    }

    public function setExpress(Request $request)
    {
        $uuid = $request->get('uuid');
        $expressName = $request->get('express_name');
        $expressNo = $request->get('express_no');
        $order = Order::where('uuid', $uuid)->first();
        if($order->status === 0){
            return $this->error('订单为未支付状态，暂不能发货');
        }
        $order->express_name = $expressName;
        $order->express_no = $expressNo;
        if($order->status == 1) {
            $order->status = 2;
        }
        $ret = $order->save();
        if($ret){
            return $this->success('发货成功!');
        } else {
            return $this->error('发货失败!');
        }
    }

    public function juanzeng()
    {
        return view('zds.order.juanzeng');
    }
    public function juanzengs()
    {
        $orders = Order::where('status', '>', 0)->orderBy('id', 'desc')->paginate(30);
        return new OrderCollection($orders);
    }
}