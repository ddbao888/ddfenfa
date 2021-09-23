<?php





use App\Http\Controllers\Controller;
use App\Model\Zds\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yansongda\Pay\Pay;

class WxxcxController extends Controller
{

    public function notify(Request $request)
    {
        error_reporting(0);
        $config = config('pay.wechat');
        $pay = Pay::wechat($config);
        $data = $pay->verify(); // 是的，验签就这么简单
        Log::info('wechat notify');
        Log::info($data);
        if (!$data) {
            return '';
        }
        $orderSn = $data->out_trade_no;
        $transactionId = $data->transaction_id;
        //$orderSn = "2019080852999750";
        //$transactionId=time();
        $this->changeOrderStatus($orderSn, $transactionId);
        return $pay->success();

    }

    public function changeOrderStatus($orderSn, $transactionId)
    {
        $order = Order::where('out_trade_no', $orderSn)->first();
        if($order == 0) {
            $order->status = 1;
            $order->transactionId = $transactionId;
            $order->save();
        }
    }

}