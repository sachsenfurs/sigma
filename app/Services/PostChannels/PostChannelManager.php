<?php

namespace App\Services\PostChannels;

use InvalidArgumentException;

class PostChannelManager
{
    public const TELEGRAM = 'telegram';
    public const BARQ = 'barq';

    public const DEFAULT = self::TELEGRAM;

    private const IMPLEMENTATIONS = [
        self::TELEGRAM => TelegramPostChannel::class,
        self::BARQ => BarqPostChannel::class,
    ];

    private const LEGACY_IMPLEMENTATIONS = [
        null => self::DEFAULT,
        '' => self::DEFAULT,
        'TelegramPostChannel::class' => self::TELEGRAM,
        TelegramPostChannel::class => self::TELEGRAM,
    ];

    public function resolve(?string $implementation): PostChannelImplementation {
        $key = self::normalize($implementation);
        $class = self::IMPLEMENTATIONS[$key] ?? null;

        if(!$class) {
            throw new InvalidArgumentException("Unknown post channel implementation [{$key}].");
        }

        return app($class);
    }

    public static function normalize(?string $implementation): string {
        if(array_key_exists($implementation, self::LEGACY_IMPLEMENTATIONS)) {
            return self::LEGACY_IMPLEMENTATIONS[$implementation];
        }

        return strtolower(trim($implementation));
    }

    public static function options(): array {
        return [
            self::TELEGRAM => 'Telegram',
            self::BARQ => 'Barq',
        ];
    }
}
