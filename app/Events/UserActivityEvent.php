<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserActivityEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $activity;
    public $request;
    public $additionalData;

    public function __construct($userId, $activity, $request = null, $additionalData = [])
    {
        $this->userId = $userId;
        $this->activity = $activity;
        $this->request = $request ?: request();
        $this->additionalData = $additionalData;
    }
}
