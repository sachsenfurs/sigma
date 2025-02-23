<div>
    <div wire:poll.30s>
        @forelse($timeslots AS $timeslot)
            <div class="card mt-3 user-select-none" wire:key="{{ $timeslot->id }}">
                <div class="card-body">
                    <div class="row flex-nowrap">
                        <div class="col-3 align-content-center text-center">
                            @if($reminder = $timeslot->userReminder?->first())
                                <div style="font-size: 0.8rem" class="text-muted"><i class="bi bi-bell icon-link"></i> {{ $reminder->offset_minutes }} min</div>
                            @endif
                            <div class="text-nowrap fs-2">{{ $timeslot->slot_start->format("H:i") }}</div>
                            <div style="font-size: 0.8rem" @class(["text-muted", "mark bg-success" => !$timeslot->slot_end->isPast() AND $timeslot->slot_start->diffInMinutes() > -15])>
                                {{ $timeslot->slot_start->diffForHumans() }}
                            </div>
                        </div>
                        <div class="col">
                            <a class="text-decoration-none fs-5" href="{{ route("timetable-entry.show", $timeslot->timetableEntry) }}">
                                {{ $timeslot->timetableEntry->sigEvent?->name_localized }}
                                ({{ $timeslot->timetableEntry->formatted_length }})
                            </a>
                            <div class="mt-2"><i class="bi bi-geo-alt icon-link"></i> {{ $timeslot->timetableEntry->sigLocation->name_localized }}</div>
                        </div>

                        <div class="col-1 p-3 d-flex align-items-center justify-content-end w-auto">
                            <button class="btn fs-4 btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside"></button>
                            <div class="dropdown-menu p-4 dropdown-menu-end text-center">
                                <div class="row gap-3 align-items-stretch">
                                    <div class="col row align-items-stretch">
                                        <button class="btn btn-outline-primary" wire:click.debounce="showEditReminderModal({{ $timeslot->id }})">
                                            <i class="bi bi-bell"></i>
                                            <div class="small">{{ __("Modify reminder") }}</div>
                                        </button>
                                    </div>
                                    <div class="col row align-items-stretch">
                                        <button class="btn btn-outline-light" wire:click.debounce="showAttendeeModal({{ $timeslot->id }})">
                                            <i class="bi bi-people-fill"></i>
                                            <div class="small">{{ __("Attendees") }}</div>
                                        </button>
                                    </div>
                                    <div class="col row align-items-stretch">
                                        <button class="btn btn-outline-danger" wire:click.debounce="removeConfirmTimeslot({{ $timeslot->id }})">
                                            <i class="bi bi-x"></i>
                                            <div class="small">{{ __("Cancel registration") }}</div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <x-infocard>
                {{ __("No registrations for any upcoming events") }}
            </x-infocard>
        @endforelse
    </div>

    <x-modal.livewire-modal id="removeConfirmModal" type="confirm" :title="__('Cancel registration').'?'" action="removeTimeslot">
        {{ __("Cancel Time Slot for :sig?", ['sig' => $selected_timeslot->timetableEntry?->sigEvent?->name_localized ?? ""]) }}
    </x-modal.livewire-modal>

    <x-modal.livewire-modal id="attendeeModal" type="alert" :title="__('Attendee List')">
        <x-slot:subtitle>
            <div class="small text-muted">{{ $selected_timeslot?->timetableEntry?->sigEvent?->name_localized }}, {{ $selected_timeslot?->slot_start?->format("H:i") }}</div>
            <div class="small">{{ $selected_timeslot?->sigAttendees?->count() . " / " . $selected_timeslot?->max_users . " " . __("Slots taken") }}</div>
        </x-slot:subtitle>

        @foreach($selected_timeslot?->sigAttendees ?? [] AS $sigAttendee)
            <div class="row mt-3">
                <div class="col-2 align-content-center text-center">
                    @if($sigAttendee->user->avatar_thumb)
                        <img src="{{ $sigAttendee->user->avatar_thumb }}" alt="" class="img-thumbnail rounded-circle">
                    @else
                        <i class="bi bi-person-circle fs-1"></i>
                    @endif
                </div>
                <div class="col align-content-center">
                    <div class="fs-4">
                        {{ $sigAttendee->user->name }}
                    </div>
                    <span class="small text-muted">{{ __("Signed up: :time", ['time' => $sigAttendee->created_at->ago()]) }}</span>
                </div>
                <div class="col-auto align-content-center">
                    @if($selected_timeslot?->getOwner() == $sigAttendee)
                        <span class="badge bg-success fs-6">{{ __("Leader") }}</span>
                    @elseif($selected_timeslot?->getOwner()?->user_id == auth()->id())
                        <button class="btn btn-outline-danger" wire:click="confirmRemoveSigAttendee({{ $sigAttendee->id }})">
                            <i class="bi bi-x"></i>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </x-modal.livewire-modal>

    <x-modal.livewire-modal id="removeAttendeeConfirmModal" type="confirm" :title="__('Confirm')" action="removeSigAttendee">
        <div class="row">
            <div class="col-2 align-content-center text-center">
                @if($this->selected_attendee?->user?->avatar_thumb)
                    <img src="{{ $this->selected_attendee->user->avatar_thumb }}" alt="" class="img-thumbnail rounded-circle">
                @else
                    <i class="bi bi-person-circle fs-1"></i>
                @endif
            </div>
            <div class="col align-content-center">
                {{ __("Do you really want to remove :attendee from this time slot?", ['attendee' => $this->selected_attendee?->user?->name]) }}
            </div>
        </div>
    </x-modal.livewire-modal>

    <livewire:sig.edit-reminder-modal wire:model="selected_timeslot" />

    <div class="w-100 my-3">
        {{ $timeslots?->links() }}
    </div>
</div>
