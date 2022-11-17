<?php

namespace App\Services\Commands;

use App\Common\Config;
use App\Jobs\SendMessageJob;
use App\Services\Base\BaseCommand;
use Illuminate\Support\Facades\Http;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Telegram;

class AboutCommand extends BaseCommand
{
    public string $name = 'about';
    public string $description = 'About';
    public string $usage = '/about';

    /**
     * @param Message $message
     * @param Telegram $telegram
     * @param int $updateId
     * @return void
     */
    public function execute(Message $message, Telegram $telegram, int $updateId): void
    {
        $chatId = $message->getChat()->getId();
        $data = [
            'chat_id' => $chatId,
            'text' => '',
        ];
        $data['text'] .= "合肥修车大队机器人\n";
        $data['text'] .= "合肥修车大队 版权所有\n";
        $data['text'] .= sprintf("Copyright (C) %s\n", date('Y'));
        $data['text'] .= "DESMG All rights reserved.\n";
        $data['text'] .= "DESMG Main API(DESMG)\n";
        $data['text'] .= sprintf("*System Time:* `%s`\n", date('Y-m-d H:i:s'));
        $data['text'] .= sprintf("*Device Name:* `%s`\n", php_uname('n'));
        $data['text'] .= sprintf("*System Version:* `%s %s %s`\n", php_uname('s'), php_uname('r'), php_uname('m'));
        $data['text'] .= sprintf("*PHP Version:* `%s %s %s`\n", PHP_VERSION, PHP_SAPI, PHP_OS);
        $data['reply_markup'] = new InlineKeyboard([]);
        $personal = new InlineKeyboardButton([
            'text' => '个人频道',
            'url' => 'https://t.me/hf_lsj',
        ]);
        $contact = new InlineKeyboardButton([
            'text' => '联系我们',
            'url' => 'https://t.me/hf_lsj',
        ]);
        $data['reply_markup']->addRow($personal, $contact);
        $github = new InlineKeyboardButton([
            'text' => '公开榜',
            'url' => 'https://t.me/hf_lsj',
        ]);
        $website = new InlineKeyboardButton([
            'text' => '报告榜',
            'url' => 'https://t.me/hf_lsj',
        ]);
        $data['reply_markup']->addRow($github, $website);
        $channel = new InlineKeyboardButton([
            'text' => '官方频道',
            'url' => 'https://t.me/hf_lsj',
        ]);
        $group = new InlineKeyboardButton([
            'text' => '官方群',
            'url' => 'https://t.me/hf_lsj',
        ]);
        $data['reply_markup']->addRow($channel, $group);
        $usage = new InlineKeyboardButton([
            'text' => '使用条款',
            'url' => 'https://t.me/hf_lsj',
        ]);
        $privacy = new InlineKeyboardButton([
            'text' => '隐私政策',
            'url' => 'https://t.me/hf_lsj',
        ]);
        $data['reply_markup']->addRow($usage, $privacy);
        $this->dispatch(new SendMessageJob($data,null,60));
    }
}
