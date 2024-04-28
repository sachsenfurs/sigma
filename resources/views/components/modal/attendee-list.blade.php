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
          @if ($timeslot->sigAttendees->first()->reg_id = auth()->user()->reg_id)
              <div>
                <h2>Teilnehmer hinzufügen</h2>
                <form class="w-100" id="attendeeForm" action="{{ route('registration.register', $timeslot->id) }}" method="POST">
                  @csrf
                  <p>Wen möchtest zu deinem Timeslot hinzufügen?</p>
                  <p><a href=""></a></p>
                  <div class="row">
                      <div class="col-4">
                          <p>Reg-Nummer</p>
                      </div>
                      <div class="col-8">
                        <input type="text" class="form-control" name="regId" placeholder="Reg-ID" value="{{ Auth::user()->reg_id }}">
                      </div>
                  </div>
                  <button type="submit" class="btn btn-primary m-1 mt-3">Teilnehmer hinzufügen</button>
                </form>
              </div>
            @endif
            <hr>
          <div>
            <h2>Teilnehmer</h2>
            @foreach ($timeslot->sigAttendees as $attendee)
              <x-list-attendee-item
                :name="$attendee->user->name"
                :avatar="$attendee->user->avatar"
                :attendee="$attendee"
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
