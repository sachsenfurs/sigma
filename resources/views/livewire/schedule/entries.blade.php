<div>
    @forelse($entry->sigEvent->timetableEntries->groupBy(fn($e) => $e->start->toDateString()) AS $date => $entries)
        <h2 class="h5 text-uppercase text-secondary mt-3">{{ \Carbon\Carbon::parse($date)->translatedFormat("l - d.m.Y") }}</h2>
        @foreach($entries AS $e)
            <div @class(["card mb-2", "bg-primary bg-opacity-10 border-primary" => $e->id == $entry->id AND $entry->sigEvent->timetableEntries->count() > 1])>
                <div class="card-body d-flex align-items-center flex-wrap gap-2">
                    @auth
                        <div class="fs-5 text-secondary" style="cursor: pointer;" wire:click.debounce="toggleFavorite({{$e->id}})">
                            <i @class(["bi mx-1", "bi-heart" => !$e->is_favorite, "bi-heart-fill text-danger" => $e->is_favorite])></i>
                        </div>
                    @endauth
                    <div class="fw-semibold fs-4">
                        {{ $e->start->format("H:i") }} - {{ $e->end->format("H:i") }}
                    </div>
                    <div class="align-self-center justify-content-end d-flex flex-grow-1">
                        @if($e->sigLocation->id != $entry->sig_location_id)
                            <a href="{{ route("locations.show", $e->sigLocation) }}" class="text-decoration-none">
                                <span class="badge bg-dark p-2 ms-1">
                                    <i class="bi bi-geo-alt icon-link"></i>
                                    {{ $e->sigLocation->name }}
                                </span>
                            </a>
                        @endif
                        @can("update", $e)
                            <span class="small">
                                <a href="{{ \App\Filament\Resources\TimetableEntryResource::getUrl('edit', ['record' => $e]) }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </span>
                        @endcan
                    </div>

                    @foreach($e->sigTimeslots as $timeslot)
                        <div class="w-100 d-md-flex align-items-center gap-3 mt-2">
                            <button class="btn btn-toggle btn-outline-secondary text-secondary bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#slot-{{$timeslot->id}}" aria-expanded="false" aria-controls="slot-{{$timeslot->id}}">
                                    <span class="ms-1"><i class="bi bi-clock icon-link"></i>
                                        {{ $timeslot->slot_start->format("H:i") }} - {{ $timeslot->slot_end->format("H:i") }}

                                        <span class="mx-1 badge text-secondary border-secondary-subtle border">
                                            <i class="bi bi-people-fill icon-link"></i> {{ $timeslot->sigAttendees->count() }} / {{ $timeslot->max_users }}
                                        </span>

                                        @if($message = (\Illuminate\Support\Facades\Gate::inspect("create", [\App\Models\SigAttendee::class, $timeslot]))->message())
                                            <span class="badge border-secondary-subtle border text-secondary">
                                                {{ $message }}
                                            </span>
                                        @endif
                                    </span>
                            </button>
                            <div class="ms-auto d-flex gap-2 mt-2 mt-md-0">
                                @auth
                                    @if($timeslot->isUserRegistered())
                                        <button class="btn btn-sm btn-outline-danger"
                                                @disabled(!$timeslot->isWithinRegTime())
                                                wire:click.debounce="unregisterSlot({{$timeslot->id}})"
                                                wire:loading.class="disabled"
                                        >
                                            <i class="bi icon-link me-1 bi-dash-circle"></i>
                                            {{ __("Sign off") }}
                                        </button>
                                    @else
                                        <button @class([
                                                    "btn btn-sm",
                                                    "btn-outline-primary" => !$timeslot->isFull(),
                                                    "btn-outline-secondary" => $timeslot->isFull(),
                                                ])
                                                @disabled($timeslot->isFull() OR !$timeslot->isWithinRegTime())
                                                wire:click.debounce="registerSlot({{$timeslot->id}})"
                                                wire:loading.class="disabled"
                                        >
                                            <i @class(["bi icon-link me-1", "bi-plus-circle" => !$timeslot->isFull(), "bi-dash-circle" => $timeslot->isFull()])></i>
                                            {{ __("Sign up") }}
                                        </button>
                                    @endif
                                @else
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route("login") }}">
                                        {{ __("Login to sign up") }}
                                    </a>
                                @endauth
                            </div>
                        </div>

                        <div class="collapse w-100 row row-cols-1 row-cols-md-2 g-1 px-2" id="slot-{{ $timeslot->id }}" wire:ignore.self>
                            @forelse($timeslot->sigAttendees AS $sigAttendee)
                                <div class="col bg-transparent d-flex align-items-center">
                                    <div class="p-2 d-flex">
                                        <x-avatar :user="$sigAttendee->user" />
                                        <span class="ms-2 fs-5 text-secondary">{{ $sigAttendee->user->name }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-secondary w-100">
                                    <p>
                                        {{ __("No one has registered for this slot yet") }}.
                                    </p>
                                    @if($timeslot->reg_start->isFuture())
                                        <span class="small">
                                            {{ __("Registration opens")." ".$timeslot->reg_start->translatedFormat("l, d.m, H:i") }}
                                        </span>
                                    @endif
                                </div>
                            @endforelse
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @empty
        {{ __("Not listed in schedule") }}
    @endforelse

    <x-modal.livewire-modal :title="__('Register')" id="registerModal" type="confirm" action="registerConfirm()">
        {{ __("Would you like to register for this event?") }}
        @if($slot = \App\Models\SigTimeslot::find($this->registerSlotId))
            <p class="text-secondary">
                {{ $slot->slot_start->translatedFormat("l, H:i") . " - " . $slot->slot_end->translatedFormat("H:i") }}
            </p>
        @endif
    </x-modal.livewire-modal>
    <x-modal.livewire-modal :title="__('Sign off')" id="unregisterModal" type="confirm" action="unregisterConfirm()">
        {{ __("Do you really want to sign off from the event?") }}
        @if($slot)
            <p class="text-secondary">
                {{ $slot->slot_start->translatedFormat("l, H:i") . " - " . $slot->slot_end->translatedFormat("H:i") }}
            </p>
        @endif
    </x-modal.livewire-modal>
</div>
