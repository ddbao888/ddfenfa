<?php


namespace App\Model\Zds;


use App\Model\Traits\HasUser;
use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionItem extends BaseModel
{
    use Uuids;
    use SoftDeletes;
    use HasUser;
    protected $table = 'zds_question_items';
    protected $guarded = [];
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}