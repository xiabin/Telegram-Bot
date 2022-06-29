<?php

namespace App\Jobs;

use App\Common\BotCommon;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class DeleteMessageJob extends TelegramBaseQueue
{
    private array $data;

    /**
     * @param array $data
     * @param int $delay
     */
    public function __construct(array $data, int $delay = 0)
    {
        parent::__construct();
        $this->data = $data;
        $this->delay($delay);
    }

    /**
     * @throws TelegramException
     */
    public function handle()
    {
        BotCommon::getTelegram();
        $serverResponse = Request::deleteMessage($this->data);
        if (!$serverResponse->isOk()) {
            $this->release(1);
        }
    }
}
