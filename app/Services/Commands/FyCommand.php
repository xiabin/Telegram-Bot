<?php

namespace App\Services\Commands;

use App\Jobs\SendMessageJob;
use App\Services\Base\BaseCommand;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Telegram;

class FyCommand extends BaseCommand
{
    public string $name = 'fy';
    public string $description = '查询发言';
    public string $usage = '/fy';
    public bool $private = false;

    /**
     * @param Message $message
     * @param Telegram $telegram
     * @param int $updateId
     * @return void
     */
    public function execute(Message $message, Telegram $telegram, int $updateId): void
    {
        $chatId = $message->getChat()->getId();
        $user  = $message->getFrom();
        $userId = $user->getId();
        $day = \App\Models\Message::queryCount($chatId, $userId, 0);
        $week = \App\Models\Message::queryCount($chatId, $userId, 1);
        $mounth = \App\Models\Message::queryCount($chatId, $userId, 2);
        $data = [
            'chat_id' => $chatId,
            'text' => '',
        ];
        $username  = $user->getUsername();
        $nickename = $user->getFirstName().$user->getLastName();
        $data['text'] .= "@{$username} 你好！{$nickename}, 发言数如下(数据有1分钟延迟)：\n";
        $data['text'] .= "1. 当天发言：$day 条;\n";
        $data['text'] .= "2. 7 天内发言：$week 条;\n";
        $data['text'] .= "3. 30 天内发言：$mounth 条;\n";
        $this->dispatch(new SendMessageJob($data, null, 0));
    }
}
