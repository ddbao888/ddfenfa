<?php


namespace App\Http\Controllers\Api\Zds;


use Yansongda\Pay\Pay;

class OrderController extends BaseController
{
    protected $config = [
        'wechat' => [
            'default' => [
                // 公众号 的 app_id
                'mp_app_id' => 'wxd3504f59c7d267f9',
                // 小程序 的 app_id
                'mini_app_id' => '',
                // app 的 app_id
                'app_id' => '',
                // 商户号
                'mch_id' => '1602530820',
                // 合单 app_id
                'combine_app_id' => '',
                // 合单商户号
                'combine_mch_id' => '',
                // 商户秘钥
                'mch_secret_key' => 'LOVE19910112LOVE1991011200000000',
                // 商户私钥
                'mch_secret_cert' => '',
                // 商户公钥证书路径
                'mch_public_cert_path' => '',
                // 微信公钥证书路径
                'wechat_public_cert_path' => '',
                'mode' => '',
            ]
        ],
        'logger' => [ // optional
            'enable' => false,
            'file' => './logs/wechat.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [ // optional
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
    ];

    public function createWxPay()
    {
        $order = [
            'out_trade_no' => time(),
            'total_fee' => '1', // **单位：分**
            'body' => 'test body - 测试',
            'openid' => 'onkVf1FjWS5SBIixxxxxxx',
        ];

        $pay = Pay::wechat($this->config)->mp($order);
        return $pay;
    }

}