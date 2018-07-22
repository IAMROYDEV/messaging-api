<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\MessageQueue;

class MessagingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $msgs = MessageQueue::orderBy('id', 'ASC')->get();
        foreach ($msgs as $msg) {
            if(!$msg) {
                return;
            }
            $host = env('POST_MESSAGE_URL');
            $sendMsgUrl ="{$host}?message={$msg->msg}&to_number={$msg->to_number}";
            if($msg->udh) {
                $sendMsgUrl .= "&udh={$msg->udh}";
            }
            Log::channel('messagelog')->info($sendMsgUrl);
            
            $msg->delete();
            sleep(env('REQUEST_FREQUENCY', 1));
        }
        return 1;
    }
}
