<?php


namespace App\Model;


use App\User;

class MgUser extends User
{
    protected $table='mg_users';
    protected $hidden = ['is_platform_manager'];
}