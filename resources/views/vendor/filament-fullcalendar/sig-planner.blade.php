@php
    $plugin = \Saade\FilamentFullCalendar\FilamentFullCalendarPlugin::get();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        {{--
        <div class="flex justify-end flex-1 mb-4">
           <x-filament-actions::actions :actions="$this->getCachedHeaderActions()" class="shrink-0" />
        </div>
         --}}



{{--        <ul x-data="{ calendar: null }" @loaded.window="calendar = $event.detail.calendar; window.calendar = calendar;">--}}

{{--            <template x-if="calendar != null">--}}
{{--                <ul>--}}
{{--                    <template x-for="res in calendar.getResources()" :key="res.id">--}}
{{--                        <li>--}}
{{--                            <label>--}}
{{--                                <x-filament::input.checkbox x-model="res.extendedProps.show_default" />--}}
{{--                                <span x-text="res.title" />--}}
{{--                            </label>--}}
{{--                        </li>--}}
{{--                    </template>--}}
{{--                </ul>--}}
{{--            </template>--}}
{{--        </ul>--}}



        <div class="filament-fullcalendar" wire:ignore ax-load
            ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-fullcalendar-alpine', 'saade/filament-fullcalendar') }}"
            ax-load-css="{{ \Filament\Support\Facades\FilamentAsset::getStyleHref('filament-fullcalendar-styles', 'saade/filament-fullcalendar') }}"
            style="position: relative; z-index: 30"
            x-ignore x-data="fullcalendar({
                locale: @js($plugin->getLocale()),
                plugins: @js($plugin->getPlugins()),
                schedulerLicenseKey: @js($plugin->getSchedulerLicenseKey()),
                timeZone: @js($plugin->getTimezone()),
                config: {...@js($plugin->getConfig()), viewDidMount: ({view}) => $dispatch('loaded', { calendar: view.calendar }) },  {{-- thanks to Dexter for this workaround! --}}
                editable: @json($plugin->isEditable()),
                selectable: @json($plugin->isSelectable()),
            })">
        </div>

    </x-filament::section>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
