<?php


namespace App\Model\Zds;


use Emadadly\LaravelUuid\Uuids;

class QuestionReward extends BaseModel
{
    use Uuids;
    protected $table = 'zds_question_rewards';

    public function good()
    {
        return $this->belongsTo(Good::class, 'good_id');
    }

}