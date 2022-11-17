<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Message extends BaseModel
{
    protected $table = 'message';



    public static function add($id, $sendChatId, $userId, $messageThreadId, $date): Builder|Model
    {
        return self::query()
            ->create(
                [
                    'sender_chat_id' => $sendChatId,
                    'id' => $id,
                    'user_id' => $userId,
                    'message_thread_id' => $messageThreadId,
                    'date' => $date,
                ]
            );
    }

    /**
     * @param $sendChatId
     * @param $userId
     * @param $period 0：天 1：周 ：2 月
     * @return mixed[]
     */
    public static function queryCount($sendChatId, $userId, $period)
    {

        DB::enableQueryLog();
        $cacheKey = "DB::Message::queryCount::{$sendChatId}::{$userId}::{$period}";
        $data = Cache::get($cacheKey);
        if ($data) {
            return $data;
        }
        $query = self::query()
            ->where('sender_chat_id', $sendChatId)
            ->where('user_id', $userId);
        switch ($period) {
            case 0:
                $query = $query->whereBetween('date', [Carbon::now()->startOfDay(), Carbon::now()]);
                break;
            case 1:
                $query = $query->whereBetween('date', [Carbon::now()->subDays(7), Carbon::now()]);
                break;
            case 2:
                $query = $query->whereBetween('date', [Carbon::now()->subDays(30), Carbon::now()]);
                break;
        }
        $data = $query->count();


        Cache::put($cacheKey, $data, Carbon::now()->addMinutes(1));
        return $data;
    }


}
