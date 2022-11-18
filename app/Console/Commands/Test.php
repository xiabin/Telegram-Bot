<?php

namespace App\Console\Commands;

use App\Common\BotCommon;
use App\Models\Message;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Test extends Command
{
    protected $signature = 'command:test';
    protected $description = 'Test';

    public function handle(): int
    {

        Log::error(123);
        return self::SUCCESS;
    }
}
