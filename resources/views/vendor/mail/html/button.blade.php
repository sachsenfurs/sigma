@props([
    'url',
    'color' => 'primary',
    'align' => 'left',
])
<div style="text-align: center; width: 100%">
<div style="margin-top:30px; padding: 40px 0 40px 0">
<a href="{{ $url }}" style="text-decoration: none; padding: 15px 50px 15px 50px; color: #000; text-transform: uppercase; font-size: 1.2rem; box-shadow: 3px 3px 0 #595959; background: #eee; border-radius: 5px" class="button" target="_blank" rel="noopener">
{{ $slot }}
</a>
</div>
</div>

