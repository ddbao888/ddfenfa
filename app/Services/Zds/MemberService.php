<?php


namespace App\Services\Zds;


use App\Model\Zds\Good;
use App\Model\Zds\Member;
use App\Model\Zds\MemberCredit;
use App\Model\Zds\MemberGoldRecord;
use App\Model\Zds\MemberRewardRecord;
use App\Model\Zds\MemberSignLog;

class MemberService extends BaseService
{
    public function store($data)
    {
        $member = new Member();
        $member->nick_name = $data['nick_name'];
        $member->avatar = $data['avatar'];
        $member->uid = $data['uid'];
        $member->unicid= $data['unicid'];
        $member->sex = $data['sex'];
        $member->gold = $data['gold'];
        $member->addtime = time();
        $ret = $member->save();
        if($ret) {
            return $this->success('添加成功!');
        } else {
            return $this->error('添加失败!');
        }
    }

    public static function addGold($member, $gold, $originId, $originType)
    {

            $memberGoldRecord = new MemberGoldRecord();
            $memberGoldRecord->uid = $member->id;
            $memberGoldRecord->gold = $gold;
            $memberGoldRecord->unicid = 1;
            $memberGoldRecord->origin_id = $originId;
            $memberGoldRecord->origin_type = $originType;
            $memberGoldRecord->save();

            $member->gold = $member->gold+$gold;
            $member->save();
    }

    public static function addCredit($member, $credit, $originId, $originType)
    {
        $memberCredit = new MemberCredit();
        $memberCredit->credit = $credit;
        $memberCredit->uid = $member->id;
        $memberCredit->origin_id = $originId;
        $memberCredit->origin_type = $originType;
        $memberCredit->unicid = 1;
        $memberCredit->addtime = time();
        $memberCredit->save();

        $member->credit = $member->credit + $credit;
        $member->save();
    }

    public static function addReward($member, $originId, $originType, $good, $unicid =1)
    {
        $memberRewardRecord = new MemberRewardRecord();
        $memberRewardRecord->uid = $member->id;
        $memberRewardRecord->unicid = $unicid;
        $memberRewardRecord->origin_id = $originId;
        $memberRewardRecord->origin_type = $originType;
        $memberRewardRecord->addtime = time();
        $memberRewardRecord->good_id = $good->id;
        $memberRewardRecord->status  = ($good->type == Good::REDBAG || $good->type == Good::GOLD) ? 1 : 0;
        $memberRewardRecord->save();

        if ($good->type == Good::REDBAG) self::addCredit($member, $good->money, $originId, $originType);

        if ($good->type == Good::GOLD) self::addGold($member, $good->gold, $originId, $originType);
    }

    public static function addSigninLog($uid, $signin_id)
    {
        $memberSigninLog = new MemberSignLog();
        $memberSigninLog->uid = $uid;
        $memberSigninLog->signin_id = $signin_id;
        $memberSigninLog->save();
    }

    public static function addAnswerNum($uid)
    {
        $member = Member::where('id', $uid)->first();
        $member->answer_num = $member->answer_num + 1;
        $member->save();
    }

    public static function addAnswerSuccessNum($uid)
    {
        $member = Member::where('id', $uid)->first();
        $member->success_num = $member->success_num + 1;
        $member->save();
    }

}
