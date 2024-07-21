@props([
    'entry',
])
<div class="accordion">
    <div class="accordion-item mt-1 mb-1">
        <div class="accordion-header">
            @if($entry->end->isAfter(now()))
               <x-timeslot.accordion-button :entry="$entry">
                   {{ $entry->getAvailableSlotCount() }} {{ __("Slots available") }}
               </x-timeslot.accordion-button>
            @else
                <x-timeslot.accordion-button :entry="$entry">
                    {{ __("Event already took place") }}
                </x-timeslot.accordion-button>
            @endif
        </div>

        <div id="panelsStayOpen-collapse-ts_{{ $entry->id }}" @class(['accordion-collapse collapse', 'show' => $entry->end > Carbon\Carbon::now()->toDateTimeString()])>
            <div class="accordion-body container text-center">
                @foreach($entry->sigTimeslots as $timeslot)
                    <div @class(['row mb-3 mb-md-0 border p-1', 'bg-secondary text-white' => $timeslot->max_users <= $timeslot->sigAttendees->count()])>
                        <div class="col-lg-4 col-6 align-self-center p-1 my-1">
                            {{ $timeslot->slot_start->format("H:i") }} - {{ $timeslot->slot_end->format("H:i")  }}
                        </div>
                        <div class="col-lg-4 col-6 align-self-center p-1 my-1">
                            <span>{{ $timeslot->sigAttendees->count() }}/{{$timeslot->max_users}}</span>
                            <span class="text-nowrap">{{ __("Slots taken") }}</span>
                            @if ($timeslot->sigAttendees->count() > 0 && auth()->check())
                                <br>
                                <i class="bi bi-people-fill"></i>
                                {{ collect($timeslot->getAttendeeNames())->pluck("name")->join(", ") }}
                            @endif
                            </div>
                        @if($timeslot->self_register)
                            @if ($timeslot->reg_start?->isAfter(now()))
                                <x-timeslot.button :disabled="true" class="btn-warning">
                                    {{ __("Registration opens") }}: {{ $timeslot->reg_start->diffForHumans() }}
                                </x-timeslot.button>
                            @elseif ($timeslot->reg_end?->isBefore(now()))
                                <x-timeslot.button :disabled="true" class="btn-secondary">
                                    {{ __("Expired") }}
                                </x-timeslot.button>
                            @else
                                @if ($timeslot->max_users > $timeslot->sigAttendees->count())
                                    @if (auth()->user()?->sigTimeslots?->find($timeslot))
                                        <x-timeslot.button
                                            class="btn-success"
                                            onclick="$('#registerModal').modal('show')
                                            .find('#registerForm')
                                            .attr('action', '{{route('registration.cancel', $timeslot)}}')"
                                        >
                                            {{ __("Already signed up") }}
                                        </x-timeslot.button>
                                    @elseif ($timeslot->timetableEntry->maxUserRegsExeeded())
                                        <x-timeslot.button :disabled="true" class="btn-secondary">
                                            {{ __("Daily limit reached") }}
                                        </x-timeslot.button>
                                    @else
                                        <x-timeslot.button
                                                class="btn-primary"
                                                onclick="$('#registerModal').modal('show')
                                                .find('#registerForm').attr('action', '{{route('registration.register', $timeslot)}}')"
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
                        @else
                            <x-timeslot.button>
                                {{ __("Please register via Con-Ops") }}
                            </x-timeslot.button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
