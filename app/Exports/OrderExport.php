<?php

namespace App\Exports;

use App\Model\Zds\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class OrderExport implements FromView
{


 /*   private $row;
    private $data;

    public function __construct($row,$data)
    {
        $this->row = $row;
        $this->data = $data;
    }*/

    public function view(): View
    {
        return view('zds.order.export', [
            'invoices' => Order::where('status', 1)->get()
        ]);
    }


    public function collection()
    {
        return Order::where('status', 1)->get();
       /* $row = $this->row;
        $data = $this->data;

        //设置表头
        foreach ($row[0] as $key => $value) {
            $key_arr[] = $key;
        }

        //输入数据
        foreach ($data as $key => &$value) {
            $js = [];
            for ($i=0; $i < count($key_arr); $i++) {
                $js = array_merge($js,[ $key_arr[$i] => $value[ $key_arr[$i] ] ]);
            }
            array_push($row, $js);
            unset($val);
        }
        return collect($row);*/
    }
}
