<?php


namespace App\Model\Zds;


class Kxian extends BaseModel
{
    protected  $table = 'zds_kxians';

    protected $appends = ['currency_name'];


    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function getCurrencyNameAttribute()
    {
        return $this->currency->name;
    }


}