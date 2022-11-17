<?php

namespace App\Console\Commands;

use App\Common\BotCommon;
use App\Common\IP;
use App\Jobs\WebhookJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Entities\Update;

class Fetch extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $telegram = BotCommon::getTelegram();

        $res = $telegram->useGetUpdatesWithoutDatabase()->handleGetUpdates();

        $results = $res->getResult();
        Log::info("收到：" . count($results) . '条消息');
        /**
         * @var $value Update
         */
        foreach ($results as $update) {
            $updateId = $update->getUpdateId();
            $this->dispatch(new WebhookJob($update, $telegram, $updateId));
        }


    }
}
