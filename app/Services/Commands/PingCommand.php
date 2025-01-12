<?php

namespace App\Services\Commands;

use App\Jobs\EditMessageTextWithKeyJob;
use App\Jobs\SendMessageWithKeyJob;
use App\Services\Base\BaseCommand;
use Carbon\Carbon;
use DESMG\RFC4122\UUID;
use Illuminate\Support\Facades\Cache;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Telegram;

class PingCommand extends BaseCommand
{
    public string $name = 'ping';
    public string $description = 'Show the latency to the bot server';
    public string $usage = '/ping';

    /**
     * @param Message $message
     * @param Telegram $telegram
     * @param int $updateId
     * @return void
     */
    public function execute(Message $message, Telegram $telegram, int $updateId): void
    {
        $key = UUID::generateUniqueID();
        $chatId = $message->getChat()->getId();
        $data = [
            'chat_id' => $chatId,
            'text' => 'Calculating...',
        ];
        $this->dispatch(new SendMessageWithKeyJob($data, $key, null));
        $data['text'] = '';
        $sendTime = $message->getDate();
        $sendTime = Carbon::createFromTimestamp($sendTime)->getTimestampMs();
        $startTime = Cache::get("TelegramUpdateStartTime_$updateId", 0);
        $startTime = Carbon::createFromTimestampMs($startTime)->getTimestampMs();
        $server_latency = $startTime - $sendTime;
        $telegramIP = Cache::get("TelegramIP_$updateId", '');
        $endTime = Carbon::now()->getTimestampMs();
        $message_latency = $endTime - $startTime;
        $data['text'] .= "*Send Time:* `$sendTime`\n";
        $data['text'] .= "*Start Time:* `$startTime`\n";
        $data['text'] .= "*End Time:* `$endTime`\n";
        $data['text'] .= "*Server Latency:* `$server_latency` ms\n";
        $data['text'] .= "*Message Latency:* `$message_latency` ms\n";
        $data['text'] .= "*Telegram Update IP:* `$telegramIP`\n";
        $IPs = [
            '149.154.175.53',
            '149.154.167.51',
            '149.154.175.100',
            '149.154.167.91',
            '91.108.56.130',
        ];
        for ($i = 1; $i <= count($IPs); $i++) {
            $IP = $IPs[$i - 1];
            $ping = $this->ping($IP);
            $data['text'] .= "*DC$i($IP) Latency:* `$ping ms`\n";
        }
        $this->dispatch(new EditMessageTextWithKeyJob($data, $key));
    }

    /**
     * @param $host
     * @return float
     */
    private function ping($host): float
    {
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_SOCKET);
        $start = Carbon::now()->getPreciseTimestamp();
        socket_sendto($socket, "\x08\x00\x19\x2f\x00\x00\x00\x00PingHost", 16, 0, $host, 0);
        socket_recv($socket, $recv, 255, 0);
        socket_sendto($socket, "\x08\x00\x19\x2f\x00\x00\x00\x00PingHost", 16, 0, $host, 0);
        socket_recv($socket, $recv, 255, 0);
        socket_sendto($socket, "\x08\x00\x19\x2f\x00\x00\x00\x00PingHost", 16, 0, $host, 0);
        socket_recv($socket, $recv, 255, 0);
        socket_sendto($socket, "\x08\x00\x19\x2f\x00\x00\x00\x00PingHost", 16, 0, $host, 0);
        socket_recv($socket, $recv, 255, 0);
        $end = Carbon::now()->getPreciseTimestamp();
        socket_close($socket);
        return ($end - $start) / 1000 / 4;
    }
}
