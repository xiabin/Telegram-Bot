<?php

namespace App\Console\Commands;

use App\Common\BotCommon;
use Illuminate\Console\Command;

class Test extends Command
{
    protected $signature = 'command:test';
    protected $description = 'Test';

    public function handle(): int
    {
        $telegram = BotCommon::getTelegram();
        $cmds = $telegram->addCommandClass();
        self::info(json_encode($cmds));
        return self::SUCCESS;
    }
}
