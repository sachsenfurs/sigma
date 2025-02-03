<x-mail::text.layout>
{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::text.subcopy>
{{ $subcopy }}
</x-mail::text.subcopy>
</x-slot:subcopy>
@endisset

<x-slot:footer>

---
{{ parse_url(config('app.url'), PHP_URL_HOST) }}
</x-slot:footer>
</x-mail::text.layout>
