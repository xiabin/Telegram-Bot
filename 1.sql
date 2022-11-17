CREATE TABLE `t_message` (
                             `sender_chat_id` bigint(20) NOT NULL COMMENT 'Sender of the message, sent on behalf of a chat',
                             `id` bigint(20) UNSIGNED NOT NULL COMMENT 'Unique message identifier',
                             `user_id` bigint(20) DEFAULT NULL COMMENT 'Unique user identifier',
                             `message_thread_id` bigint(20) DEFAULT NULL COMMENT 'Unique identifier of a message thread to which the message belongs; for supergroups only',
                             `date` timestamp NULL DEFAULT NULL COMMENT 'Date the message was sent in timestamp format',
                             `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                             `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
                             `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
