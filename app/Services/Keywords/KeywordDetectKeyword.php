<?php

namespace App\Services\Keywords;

use App\Jobs\SendMessageJob;
use App\Models\TChatKeywords;
use App\Services\Base\BaseKeyword;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Telegram;

class KeywordDetectKeyword extends BaseKeyword
{
    public string $name = 'Keyword Detecter';
    public string $description = 'Match Keywords';
    protected string $pattern = '//';

    public function preExecute(Message $message): bool
    {
        return true;
    }

    public function execute(Message $message, Telegram $telegram, int $updateId): void
    {
        Log::info("KeywordDetectKeyword::execute");
        $keywords = TChatKeywords::getKeywords($message->getChat()->getId());
        foreach ($keywords as $keyword) {
            $this->handle(
                $keyword['keyword'],
                $keyword['target'],
                $keyword['operation'],
                $keyword['data'],
                $message,
                $telegram,
                $updateId
            );
        }
    }

    private function handle(string $keyword, string $target, string $operation, array $data, Message $message, Telegram $telegram, int $updateId)
    {

        $chatId = $message->getChat()->getId();
        $messageId = $message->getMessageId();
        $text = $message->getText();
        $data = [
            'chat_id' => $chatId,
            'reply_to_message_id' => $messageId,
            'text' => '我收到了你的消息: '.$text."  ".date('Y-m-d H:i:s'),
        ];
        $this->dispatch(new SendMessageJob($data, null, 0));
    }
}
