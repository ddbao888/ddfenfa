<?php

namespace App\Jobs;

use App\Services\ChatService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PushNoticeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $question;
    protected  $good;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $question, $good)
    {
        //
        $this->question = $question;
        $this->user = $user;
        $this->good = $good;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $onLineUsers = ChatService::getUsersByRoom($this->question->uuid);
        foreach($onLineUsers as $item)
        {
            ChatService::sendMessage($item['fd'], 1, $this->user->nick_name.$this->question->pass_type == 1 ? '累计' : '连续'.'回答题获'.$this->good->good_title);
        }
    }
}
