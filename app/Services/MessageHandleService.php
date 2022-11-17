<?php

namespace App\Services;

use App\Services\Base\BaseService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;

class MessageHandleService extends BaseService
{
    /**
     * @var array
     */
    private array $handlers;

    /**
     * @param Update $update
     * @param Telegram $telegram
     * @param int $updateId
     * @return void
     * @throws TelegramException
     * @throws BindingResolutionException
     */
    public function handle(Update $update, Telegram $telegram, int $updateId): void
    {
        $message = $update->getMessage();
        $messageType = $message->getType();
        Log::info("消息类型是 $messageType");
        if(in_array($messageType,['voice','video','sticker','photo','audio','text'])){
            $this->saveMessage($message);
        }

//        $this->addHandler('ANY', KeywordHandleService::class);
        $this->addHandler('command', CommandHandleService::class);


        if($messageType)
//        $this->addHandler('sticker', StickerHandleService::class);
        $this->runHandler($messageType, $message, $telegram, $updateId);
//
    }

    /**
     * @param string $needType
     * @param string $class
     */
    private function addHandler(string $needType, string $class)
    {
        $this->handlers[] = [
            'type' => $needType,
            'class' => $class,
        ];
    }

    /**
     * @param string $type
     * @param Message $message
     * @param Telegram $telegram
     * @param int $updateId
     * @return void
     * @throws BindingResolutionException
     * @throws TelegramException
     */
    private function runHandler(string $type, Message $message, Telegram $telegram, int $updateId): void
    {
        foreach ($this->handlers as $handler) {
            if ($type == $handler['type'] || $handler['type'] == '*' || $handler['type'] == 'ANY') {
                $handled = app()->make($handler['class'])->handle($message, $telegram, $updateId);
                if ($handled) {
                    return;
                }
            }
        }
    }


    private function saveMessage(Message $message){
        $via_bot = $message->getViaBot();
        if ($via_bot) {
            //todo
        }else{
            $id = $message->getMessageId();
            $chatId = $message->getChat()->getId();
            $userId = $message->getFrom()->getId();
            $messageThreadId = $message->getMessageThreadId();
            $date = date('Y-m-d H:i:s', $message->getDate() ?? time());
            \App\Models\Message::add($id,$chatId,$userId,$messageThreadId,$date);
        }


    }
}
