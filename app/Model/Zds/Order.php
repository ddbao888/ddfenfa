<?php


namespace App\Model\Zds;


use Emadadly\LaravelUuid\Uuids;

class Order extends BaseModel
{
    protected  $table = 'zds_orders';
    protected  $appends = ['amount'];

    public function getAmountAttribute()
    {
        return $this->num * $this->good_price;
    }
}