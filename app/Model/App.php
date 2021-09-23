<?php


namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class App extends Model
{
    use SoftDeletes;
    protected  $appends = ['url'];

    public function getUrlAttribute()
    {
        return env("APP_URL")."/app/".$this->id;
    }
}