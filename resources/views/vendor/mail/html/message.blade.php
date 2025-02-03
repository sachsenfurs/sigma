<x-mail::html.layout>
    {{-- Header --}}
    <x-slot:header>
        <x-mail::html.header :url="config('app.url')" />
    </x-slot:header>

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        <x-slot:subcopy>
            <x-mail::html.subcopy>
                {{ $subcopy }}
            </x-mail::html.subcopy>
        </x-slot:subcopy>
    @endisset

    {{-- Footer --}}
    <x-slot:footer>
        <x-mail::html.footer>
            {{ parse_url(config('app.url'), PHP_URL_HOST) }}
        </x-mail::html.footer>
    </x-slot:footer>
</x-mail::html.layout>
