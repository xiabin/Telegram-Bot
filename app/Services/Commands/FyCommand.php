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
        $userId = $message->getFrom()->getId();
        $day = \App\Models\Message::queryCount($chatId, $userId, 0);
        $week = \App\Models\Message::queryCount($chatId, $userId, 1);
        $mounth = \App\Models\Message::queryCount($chatId, $userId, 2);
        $data = [
            'chat_id' => $chatId,
            'text' => '',
        ];
        $data['text'] .= "发言数如下(统计数据有 1 分钟延迟)：\n";
        $data['text'] .= "当天发言：$day 条;\n";
        $data['text'] .= "7 天内发言：$week 条;\n";
        $data['text'] .= "30 天内发言：$mounth 条;\n";
        $this->dispatch(new SendMessageJob($data, null, 0));
    }
}
