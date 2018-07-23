<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MessageCreateRequest;
use App\Services\MessageUdhService;
use App\MessageQueue;

class MessageController extends Controller {

    /**
     * @var App\Services\MessageUdhService
     */
    protected $messageUdhService;

    /**
     * Instantiate a new controller instance.
     *
     * @param  MessageUdhService  $messageUdhService
     * @return void
     */
    public function __construct(MessageUdhService $messageUdhService) {
        $this->messageUdhService = $messageUdhService;
    }

    /**
     * Send message and log messages in message.log file
     * 
     * @param MessageCreateRequest $request
     * @return type
     */
    public function store(MessageCreateRequest $request) {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json(['status' => 400, 'message' => 'Validation Failed', 'errors' => $request->validator->messages()], 400);
        }
        try {
            $params = $request->all();
            $host = env('POST_MESSAGE_URL');
            $msgs = $this->messageUdhService->createMsg($params['text']);
            foreach ($msgs as $value) {
                extract($value);
                MessageQueue::create(['msg' => $msg, 'udh' => $udh, 'to_number' => $params['to_number']]);
            }
            $count = count($msgs);
            return response()->json([
                        'status' => 200,
                        'message' => "{$count} message(s) dispatched"
            ]);
        } catch (\Exception $e) {
            return $e;
        }
    }

}
