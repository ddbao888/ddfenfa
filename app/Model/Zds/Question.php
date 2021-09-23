<?php


namespace App\Model\Zds;


use App\Model\Comment;
use Emadadly\LaravelUuid\Uuids;

class Question extends BaseModel
{
    use Uuids;
    protected $table = 'zds_questions';
    protected $hidden = ['uid', 'unicid'];

    public function questionCate()
    {
        return $this->belongsTo(QuestionCate::class, 'question_cate_id');
    }

    public function questionRewards()
    {
        return $this->hasMany(QuestionReward::class, 'question_id');
    }

    public function questionItems()
    {
        return $this->hasMany(QuestionItem::class, 'question_id');
    }

    public function likes()
    {
        return $this->hasMany(QuestionLikeLog::class, 'question_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'origin_id')->where('origin_type', 'question');
    }
}