<?php

namespace App\Console\Commands;

use App\Common\BotCommon;
use App\Models\Message;
use Illuminate\Console\Command;

class Test extends Command
{
    protected $signature = 'command:test';
    protected $description = 'Test';

    public function handle(): int
    {

        $data  =  Message::queryCount(-1001850206305,5370041724,0);
        self::info($data);
        return self::SUCCESS;
    }
}
