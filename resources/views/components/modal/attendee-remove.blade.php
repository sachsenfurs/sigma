@props([
    'sigAttendee'
])
<div class="modal fade" id="removeAttendeeModal{{ $sigAttendee->id }}" tabindex="-1" role="dialog" aria-labelledby="removeAttendeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <form id="removeAttendeeForm{{ $sigAttendee->id }}" action="" method="POST">
            @csrf
            @method('delete')
            <div class="modal-header">
                <h5 class="modal-title" id="removeAttendeeModalLabel">{{ __("Remove Attendee") }}</h5>
            </div>
            <div class="modal-body">
                <p>{{ __('Do you really want to remove :attendee from your booking?', [ 'attendee' => $sigAttendee->user->name ])}}</p>
            </div>
            <input type="hidden" class="form-control" name="regId" placeholder="Reg-ID" value="{{ $sigAttendee->user->reg_id }}">
            <div class="modal-footer" style="display: flex; justify-content: center;">
                <a class="btn btn-secondary" onclick="$('#removeAttendeeModal{{ $sigAttendee->id }}').modal('toggle')" data-dismiss="reminderModal">{{ __("Close") }}</a>
                <button type="submit" class="btn btn-danger">{{ __("Remove") }}</button>
            </div>
        </form>
      </div>
    </div>
</div>
