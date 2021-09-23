<?php


namespace App\Model\Zds;


use App\Model\MemberWithdraw;
use Emadadly\LaravelUuid\Uuids;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use Uuids;
    protected $table = 'zds_members';
    protected  $hidden = ['wx_openid'];
    protected function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function rewards()
    {
        return $this->hasMany(MemberRewardRecord::class, 'uid');
    }

    public function withdraws()
    {
        return $this->hasMany(MemberWithdraw::class, 'uid');
    }

    public function answerlogs()
    {
        return $this->hasMany(MemberAnswerlog::class, 'uid');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
