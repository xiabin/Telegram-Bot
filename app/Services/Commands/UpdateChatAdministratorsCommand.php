<?php

namespace App\Services\Commands;

use App\Jobs\SendMessageJob;
use App\Models\TChatAdmins;
use App\Services\Base\BaseCommand;
use Longman\TelegramBot\Entities\ChatMember\ChatMember;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Throwable;

class UpdateChatAdministratorsCommand extends BaseCommand
{
    public string $name = 'updatechatadministrators';
    public string $description = 'Update Chat Administrators';
    public string $usage = '/updatechatadministrators';

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
            'reply_to_message_id' => $message->getMessageId(),
            'text' => '',
        ];
        $chatType = $message->getChat()->getType();
        if (!in_array($chatType, ['group', 'supergroup'], true)) {
            $data['text'] .= "*Error:* This command is available only for groups.\n";
            $this->dispatch(new SendMessageJob($data));
            return;
        }
        $response = Request::getChatAdministrators([
            'chat_id' => $chatId,
        ]);
        /** @var ChatMember[] $admins */
        $admins = $response->getResult();
        try {
            TChatAdmins::clearAdmin($chatId);
            $i = 0;
            foreach ($admins as $admin) {
                $i++;
                TChatAdmins::addAdmin($chatId, $admin->getUser()->getId());
            }
            $data['text'] .= "Updated chat administrators successfully.\n";
            $data['text'] .= "*This group is a* `$chatType`.\n";
            $data['text'] .= "*There are* `$i` admins in this group.\n";
        } catch (Throwable $e) {
            $data['text'] .= "*Error({$e->getCode()}):* database error.\n";
        }
        $this->dispatch(new SendMessageJob($data));
    }
}
