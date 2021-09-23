<?php


namespace App\Model\Zds;


class Post extends BaseModel
{
    protected $table = 'zds_posts';

    protected $guarded = ['is_plat_manager'];
}