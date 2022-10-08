<?php

namespace App\Console\Schedule\WellKnownSoftwareUpdateSubscribe\Softwares;

use App\Console\Schedule\WellKnownSoftwareUpdateSubscribe\SoftwareInterface;
use JetBrains\PhpStorm\ArrayShape;
use Longman\TelegramBot\Entities\InlineKeyboard;

class KernelFeodra implements SoftwareInterface
{

    public function getVersion(): string
    {
        // TODO: Implement getVersion() method.
    }

    #[ArrayShape([
        'chat_id' => 'int',
        'text' => 'string',
        'reply_markup' => InlineKeyboard::class,
    ])]
    public function generateMessage(int $chat_id, string $version): array
    {
        // TODO: Implement generateMessage() method.
    }
}
