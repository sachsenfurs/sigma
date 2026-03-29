@php
    $plugin = \Saade\FilamentFullCalendar\FilamentFullCalendarPlugin::get();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
{{--        <div class="flex justify-end flex-1 mb-4">--}}
{{--            <x-filament::actions :actions="$this->getCachedHeaderActions()" class="shrink-0" />--}}
{{--        </div>--}}

{{--        <div class="filament-fullcalendar" wire:ignore x-load--}}
{{--            x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-fullcalendar-alpine', 'saade/filament-fullcalendar') }}"--}}
{{--            ax-load-css="{{ \Filament\Support\Facades\FilamentAsset::getStyleHref('filament-fullcalendar-styles', 'saade/filament-fullcalendar') }}"--}}
{{--            style="position: relative; z-index: 30"--}}
{{--            x-ignore x-data="fullcalendar({--}}
{{--                locale: @js($plugin->getLocale()),--}}
{{--                plugins: @js($plugin->getPlugins()),--}}
{{--                schedulerLicenseKey: @js($plugin->getSchedulerLicenseKey()),--}}
{{--                timeZone: @js($plugin->getTimezone()),--}}
{{--                config: {...@js($plugin->getConfig()), viewDidMount: ({view}) => $dispatch('loaded', { calendar: view.calendar }) },  --}}{{-- thanks to Dexter for this workaround! --}}
{{--                editable: @json($plugin->isEditable()),--}}
{{--                selectable: @json($plugin->isSelectable()),--}}
{{--                eventClassNames: {!! htmlspecialchars($this->eventClassNames(), ENT_COMPAT) !!},--}}
{{--                eventContent: {!! htmlspecialchars($this->eventContent(), ENT_COMPAT) !!},--}}
{{--                eventDidMount: {!! htmlspecialchars($this->eventDidMount(), ENT_COMPAT) !!},--}}
{{--                eventWillUnmount: {!! htmlspecialchars($this->eventWillUnmount(), ENT_COMPAT) !!},--}}
{{--            })">--}}
{{--        </div>--}}

        <div class="flex justify-end flex-1 mb-4">
            <x-filament::actions :actions="$this->getCachedHeaderActions()" class="shrink-0" />
        </div>

        <div wire:ignore x-load
             x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-fullcalendar-alpine', 'saade/filament-fullcalendar') }}"
             x-ignore x-data="fullcalendar({
                locale: @js($plugin->getLocale()),
                plugins: @js($plugin->getPlugins()),
                schedulerLicenseKey: @js($plugin->getSchedulerLicenseKey()),
                timeZone: @js($plugin->getTimezone()),
                config: {...@js($plugin->getConfig()), viewDidMount: ({view}) => $dispatch('loaded', { calendar: view.calendar }) },
                editable: @json($plugin->isEditable()),
                selectable: @json($plugin->isSelectable()),
                eventClassNames: {!! htmlspecialchars($this->eventClassNames(), ENT_COMPAT) !!},
                eventContent: {!! htmlspecialchars($this->eventContent(), ENT_COMPAT) !!},
                eventDidMount: {!! htmlspecialchars($this->eventDidMount(), ENT_COMPAT) !!},
                eventWillUnmount: {!! htmlspecialchars($this->eventWillUnmount(), ENT_COMPAT) !!},
            })" class="filament-fullcalendar"></div>


        <div x-data="{ calendar: null, resources: @js($resources), selectAll: false}" @loaded.window="calendar = $event.detail.calendar" class="pb-6">
            <div class="py-4">
                <x-filament::button
                    class="p-6"
                    @click="(selectAll = !selectAll) ? resources.map((r) => calendar.addResource(r)) : calendar.getResources().map((r) => r.remove())"
                >

                    {{ __("Select all") }}
                </x-filament::button>
            </div>
            <template x-if="calendar != null">
                <div class="grid gap-2 xl:grid-cols-5">
                    <template x-for="res in resources" :key="res.id">
                        <div>
                            <label>
                                <x-filament::input.checkbox
                                    x-bind:checked="selectAll || calendar.getResourceById(res.id) !== null"
                                    @change="$event.target.checked ? calendar.addResource(res) : calendar.getResourceById(res.id).remove()"
                                />
                                <span x-text="res.title + (res.description ? ' - ' + res.description : '')" />
                            </label>
                        </div>
                    </template>
                </div>
            </template>
        </div>

    </x-filament::section>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
