<?php

namespace App\Services\PostChannels;

use App\Models\Post\Post;
use App\Models\Post\PostChannel;
use App\Models\Post\PostChannelMessage;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\Exceptions\TelegramOtherException;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramPostChannel implements PostChannelImplementation
{
    public function send(PostChannel $channel, Post $post): void {
        if($messageId = $this->sendPost($channel, $post)) {
            $post->channels()->attach([
                [
                    'post_channel_id' => $channel->id,
                    'message_id' => $messageId,
                ]
            ]);
        }
    }

    public function sendTest(PostChannel $channel, Post $post): void {
        if($channel->test_channel_identifier) {
            $this->sendPost($channel, $post, true);
        }
    }

    public function update(PostChannelMessage $message): void {
        try {
            if($message->post->image) {
                Telegram::editMessageCaption([
                    'chat_id' => $message->postChannel->channel_identifier,
                    'message_id' => $message->message_id,
                    'caption' => $message->post->getTranslatedText($message->postChannel->language),
                    'parse_mode' => Post::$parseMode,
                ]);
            } else {
                Telegram::editMessageText([
                    'chat_id' => $message->postChannel->channel_identifier,
                    'message_id' => $message->message_id,
                    'parse_mode' => Post::$parseMode,
                    'text' => $message->post->getTranslatedText($message->postChannel->language),
                ]);
            }
        } catch(TelegramOtherException|TelegramResponseException $e) {
            // Message not found or no longer editable.
            report($e);
        }
    }

    public function delete(PostChannelMessage $message): void {
        try {
            Telegram::deleteMessage([
                'chat_id' => $message->postChannel->channel_identifier,
                'message_id' => $message->message_id,
            ]);
        } catch(TelegramResponseException $e) {
            // Message was already deleted or is no longer reachable.
            report($e);
        }
    }

    private function sendPost(PostChannel $channel, Post $post, bool $test = false): string|false {
        $text = $post->getTranslatedText($channel->language);
        $targetChatId = $test ? $channel->test_channel_identifier : $channel->channel_identifier;

        if(!$targetChatId) {
            return false;
        }

        if($post->image) {
            $response = Telegram::sendPhoto([
                'chat_id' => $targetChatId,
                'photo' => InputFile::create(Storage::disk("public")->path($post->image)),
                'caption' => $text,
                'parse_mode' => Post::$parseMode
            ]);
        } else {
            $response = Telegram::sendMessage([
                'chat_id' => $targetChatId,
                'text' => $text,
                'parse_mode' => Post::$parseMode
            ]);
        }

        return $response->isError() ? false : (string) $response->getMessageId();
    }
}
