@props([
    'timeslot',
])
<div class="modal fade" id="attendeeListModal{{ $timeslot->id }}" tabindex="-1" role="dialog" aria-labelledby="attendeeListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="attendeeListModalLabel">{{ __("Attendee List") }}</h5>
        </div>
        <div class="modal-body">
          @if ($timeslot->timetableEntry->sigEvent->group_registration_enabled && $timeslot->sigAttendees->where('user_id', auth()->user()->id)->where('timeslot_owner', true)->first())
              <div>
                <h2>{{ __('Add attendee') }}</h2>
                <form class="w-100" id="attendeeForm" action="{{ route('registration.register', $timeslot->id) }}" method="POST">
                  @csrf
                  <p>{{ __('Who would you like to add to your timeslot?') }}</p>
                  <p><a href=""></a></p>
                  <div class="row">
                      <div class="col-4">
                          <p>{{ __('Reg-Number') }}</p>
                      </div>
                      <div class="col-8">
                        <input type="text" class="form-control" name="regId" placeholder="Reg-ID" value="{{ Auth::user()->reg_id }}">
                      </div>
                  </div>
                  <button type="submit" class="btn btn-primary m-1 mt-3">{{ __('Add attendee') }}</button>
                </form>
              </div>
              <hr>
            @endif
          <div>
            <h2>{{ __('Attendees') }}</h2>
            @foreach ($timeslot->sigAttendees as $attendee)
              <x-list-attendee-item
                :name="$attendee->user->name"
                :avatar="$attendee->user->avatar_thumb"
                :attendee="$attendee"
                :canManageSigAttendees="$timeslot->sigAttendees->where('user_id', auth()->user()->id)->where('timeslot_owner', true)->first()"
                :groupRegistrationEnabled="$timeslot->timetableEntry->sigEvent->group_registration_enabled"
              />
              @if ($attendee->user->id != auth()->user()->id)
                <x-modal.attendee-remove :sigAttendee="$attendee" />
              @endif
            @endforeach
          </div>
        </div>
        <div class="modal-footer" style="display: flex; justify-content: center;">
            <a class="btn btn-secondary" onclick="$('#attendeeListModal{{ $timeslot->id }}').modal('toggle');" data-dismiss="modal">{{ __("Close") }}</a>
        </div>
      </div>
    </div>
</div>
