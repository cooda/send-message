<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    protected $to;

    protected $message;

    /**
     * Create a new job instance.
     *
     * @param $to
     * @param $message
     */
    public function __construct($to, $message)
    {
        $this->to = $to;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $response = Http::retry(3, 100)->post('https://send.message/api/send', [
            'to'      => $this->to,
            'message' => $this->message,
        ]);

        if ($response->failed()) {
            throw new Exception('Response error');
        }
    }
}
