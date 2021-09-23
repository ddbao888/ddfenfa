<?php


namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class MgMenu extends Model
{
    protected $guarded = [];


    public function childs()
    {
        return $this->hasMany(get_class($this), 'parent_id', $this->getKeyName());
    }

}