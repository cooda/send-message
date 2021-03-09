<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Jobs\SendMessage;

class MessageController extends Controller
{
    public function send(SendMessageRequest $request)
    {
        foreach ($request->input('to') as $to) {
            SendMessage::dispatch($to, $request->input('message'));
        }
    }
}
