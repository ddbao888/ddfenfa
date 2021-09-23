<?php


namespace App\Model\Zds;


use App\Model\Traits\HasUser;
use Emadadly\LaravelUuid\Uuids;

class Good extends BaseModel
{
    const REDBAG = 1;
    const GOLD = 2;
    const ENTIRY = 3;

    protected $types = ['红包', '金币', '实物','1','2'];

    use Uuids;
    use HasUser;

    protected $appends = ['type_name'];
    protected $table = 'zds_goods';

    public function getTypeNameAttribute()
    {
        return $this->types[$this->type-1];
    }
}
