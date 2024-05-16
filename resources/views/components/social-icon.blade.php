@props([
    'icon' => "",
])
<i {{ $attributes->class([
    'bi',
    'bi-twitter-x'      => $icon == "twitter" || $icon == "x",
    'bi-telegram'       => $icon == "telegram",
    'bi-discord'        => $icon == "discord",
    'bi-instagram'      => $icon == "instagram",
    ]) }}></i>
