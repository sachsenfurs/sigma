@props([
    'entry',
])
<div class="accordion">
    <div class="accordion-item mt-1 mb-1">
        <div class="accordion-header">
           @if($entry->end->isAfter(now()))
               <x-timeslot.accordion-button :entry="$entry">
                   {{ $entry->getAvailableSlotCount() }} Freie Plätze
               </x-timeslot.accordion-button>
           @elseif($entry->end->addHours(12)->isAfter(now()))
                <x-timeslot.accordion-button :entry="$entry">
                    Event hat bereits stattgefunden
                </x-timeslot.accordion-button>
           @endif
        </div>

        <div id="panelsStayOpen-collapse-ts_{{ $entry->id }}" @class(['accordion-collapse collapse', 'show' => $entry->end > Carbon\Carbon::now()->toDateTimeString()])>
            <div class="accordion-body container text-center">
                @foreach($entry->sigTimeslots as $timeslot)
                    <div @class(['row mb-3 mb-md-0 border p-1', 'bg-secondary text-white' => $timeslot->max_users <= $timeslot->sigAttendees->count()])>
                        <div class="col-lg-4 col-6 align-self-center">
                            {{ date('H:i', strtotime($timeslot->slot_start)) }} - {{ date('H:i', strtotime($timeslot->slot_end))  }}
                        </div>
                        <div class="col-lg-4 col-6 align-self-center">
                            <span>{{ $timeslot->sigAttendees->count() }}/{{$timeslot->max_users}}</span>
                            <span class="text-nowrap">Plätze belegt</span>
                        </div>

                        @if ($timeslot->reg_start->isAfter(now()))
                            <x-timeslot.button :disabled="true" class="btn-warning">
                                {{ __("Registration opens") }}: {{ $timeslot->reg_start->diffForHumans() }}
                            </x-timeslot.button>
                        @elseif ($timeslot->reg_end->isBefore(now()))
                            <x-timeslot.button :disabled="true" class="btn-secondary">
                                {{ __("Expired") }}
                            </x-timeslot.button>
                        @else
                            @if ($timeslot->max_users > $timeslot->sigAttendees->count())
                                @if (auth()->user()?->sigTimeslots?->find($timeslot))
                                    <x-timeslot.button :disabled="true" class="btn-success">
                                        {{ __("Already signed up") }}
                                    </x-timeslot.button>
                                @elseif ($timeslot->timetableEntry->maxUserRegsExeeded())
                                    <x-timeslot.button :disabled="true" class="btn-secondary">
                                        {{ __("Daily limit reached") }}
                                    </x-timeslot.button>
                                @else
                                    <x-timeslot.button
                                        class="btn-primary"
                                        onclick="$('#registerModal').modal('show'); $('#registerForm').attr('action', '{{route('registration.register', $timeslot)}}')"
                                    >
                                        {{ __("Sign up") }}
                                    </x-timeslot.button>
                                @endif
                            @else
                                <x-timeslot.button :disabled="true" class="btn-secondary">
                                    {{ __("Sold out") }}
                                </x-timeslot.button>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
