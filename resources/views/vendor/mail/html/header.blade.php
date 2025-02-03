@props(['url'])
<a href="{{ $url }}" class="header_a" style="text-decoration: none">
<div class="header" style="text-align: center; margin-bottom: 20px; display: flex; align-items: center; justify-content: center">
    <img style="max-width: 100px;" src="https://east.sachsenfurs.de/images/logo.png" alt="{{ app(\App\Settings\AppSettings::class)->event_name }}">
    <h2 style="padding-left: 1rem">
        {{ $slot }}
    </h2>
</div>
</a>
