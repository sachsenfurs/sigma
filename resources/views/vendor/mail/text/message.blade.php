<x-mail::layout>
{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

<x-slot:footer>

---
{{ parse_url(config('app.url'), PHP_URL_HOST) }}
</x-slot:footer>
</x-mail::layout>
