<div wire:poll.120s>
    <div class="row row-cols-1 row-cols-xl-2 row-cols-xxl-3 g-3 align-items-stretch user-select-none">
        @forelse($favorites AS $favorite)
            <div class="col d-flex" wire:key="{{ $favorite->id }}">
                <div class="card flex-grow-1">
                    <div class="card-body align-content-center">
                        <div class="row flex-nowrap">
                            <div class="col-3 align-content-center text-center">
                                @if($reminder = $favorite->user_reminder)
                                    <div style="font-size: 0.8rem" class="text-muted"><i class="bi bi-bell icon-link"></i> {{ $reminder->offset_minutes }} min</div>
                                @endif
                                <div class="text-nowrap fs-3">{{ $favorite->timetableEntry->start->format("H:i") }}</div>
                                <div style="font-size: 0.8rem" @class(["text-muted", "mark bg-success" => !$favorite->timetableEntry->end->isPast() AND $favorite->timetableEntry->start->diffInMinutes() > -15])>
                                    {{ $favorite->timetableEntry->start->diffInHours() < -18 ? $favorite->timetableEntry->start->translatedFormat("l") : $favorite->timetableEntry->start->diffForHumans() }}
                                </div>
                            </div>
                            <div class="col pe-1">
                                <a class="text-decoration-none fs-6" href="{{ route("timetable-entry.show", $favorite->timetableEntry) }}">
                                    {{ $favorite->timetableEntry->sigEvent?->name_localized }}
                                    ({{ $favorite->timetableEntry->formatted_length }})
                                </a>
                                <div class="mt-2"><i class="bi bi-geo-alt icon-link"></i> {{ $favorite->timetableEntry->sigLocation->name_localized }}</div>
                            </div>

                            <div class="col-auto ps-1 d-flex align-items-center justify-content-end w-auto text-center">
                                <div type="button" class="text-muted" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                    @if($reminder = $favorite->reminders->first())
                                        <div class="bi bi-bell fs-3"></div>
                                        <span class="small">
                                            {{ $reminder->offset_minutes }} min
                                        </span>
                                    @else
                                        <div class="bi bi-bell-slash fs-3 text-dark"></div>
                                    @endif
                                </div>
                                <div class="dropdown-menu p-4 dropdown-menu-end">
                                    <div class="d-flex gap-3 ">
                                        <label for="minutes" class="small">{{ __("How many minutes before the event would you like to be notified?") }}</label>
                                        <select name="minutes" id="minutes" class="form-select" wire:change.debounce="setReminderTime({{$favorite->id}})" wire:model="minutes.{{$favorite->id}}">
                                            @foreach(range(15, 60, 15) AS $min)
                                                <option value="{{ $min }}">{{ $min . " " . __("Minutes") }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
    </div>
        <x-infocard>
            {{ __("No favorite events") }}
            <p class="mt-3">
                <a class="btn btn-outline-light" href="{{ route("schedule.listview") }}">{{ __("Explore Events") }}</a>
            </p>
        </x-infocard>
    @endforelse

    <div class="w-100 mt-4">
        {{ $favorites?->links(data: ['scrollTo' => '#favorites']) }}
    </div>
</div>
