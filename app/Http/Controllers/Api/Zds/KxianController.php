<?php


namespace App\Http\Controllers\Api\Zds;


use App\Model\Zds\Currency;
use App\Model\Zds\Kxian;
use App\Repository\Zds\KxianRep;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KxianController extends BaseController
{
    public function index(Request $request)
    {
        $currencyName = $request->get('currency');
        $currencies = Currency::orderBy('id')->select(['id', 'name'])->get();
        if(!$currencyName){
            $currency = Currency::orderBy('id')->first();
        } else {
            $currency = Currency::where('name', $currencyName)->first();
        }
        $data = [];
        $cycles = ['4H','1H'];
        $openTime = 4;

        foreach($cycles as $cycle) {
            $kxians = Kxian::where('currency_id', $currency->id)->where('cycle', $cycle)->orderBy('id','desc')->take(30)->get();
            $pics = [];
            foreach($kxians as $item) {
                $item->status = 1;
                $item->save();
                $pics[] = ['image' => $item->pic, 'title' => $item->created_at->toDateTimeString()];
            }
            //$minute = $this->doDaoJiShi($cycle);
            $minute = 0;
            $data[] = ['slider' =>100, 'minute' => $minute,'currency' => $currency->name, 'cycle' => $cycle, 'pics' => $pics];

        }
        return $this->success('成功', ['show' => true, 'openTime' => $openTime,'currencies' => $currencies, 'data' =>$data]);
    }

    private function doDaoJiShi($cycle, $openTime = 5)
    {
        $houD = 0;
        if($openTime == 5 && $cycle == '4H') {
            $times = [9, 13, 17, 21, 0];
            $hour = Carbon::now()->addMinute(10)->hour;

            foreach($times as $key => $i)
            {
                if($hour < $i && $hour != 0) {
                    $houD = $times[$key];
                    break;
                }
            }
        }
        if($openTime == 5 && $cycle == '1H') {
            $times = [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 0];
            $hour = Carbon::now()->addMinute(10)->hour;

            foreach($times as $key => $i)
            {
                if($hour < $i && $hour != 0) {
                    $houD = $times[$key];
                    break;
                }
            }
        }

        return Carbon::parse(Carbon::now()->format('Y-m-d '.$houD.':00:00'))->diffInMinutes(Carbon::now()) * 60;

    }
}