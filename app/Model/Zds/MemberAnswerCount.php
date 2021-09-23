<?php


namespace App\Model\Zds;


class MemberAnswerCount extends BaseModel
{
    protected  $table = 'zds_member_answer_counts';

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}