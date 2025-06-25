@props(['url'])
<div class="header">
<a href="{{ $url }}" style="text-decoration: none">
<div class="header" style="text-align: center; margin-bottom: 20px; display: flex; align-items: center; justify-content: center">
<img style="max-width: 100px;" src="{{ app(\App\Settings\AppSettings::class)->logoUrl()  }}" alt="{{ app(\App\Settings\AppSettings::class)->event_name }}">
<h2 style="padding-left: 1rem">
{{ $slot }}
</h2>
</div>
</a>
</div>
