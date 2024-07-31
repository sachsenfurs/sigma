@props([
  'userChatDepartments',
])
<div class="modal fade" id="newChatModal" tabindex="-1" role="dialog" aria-labelledby="newChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <form method="POST" action="{{ route('chats.create') }}">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="newChatModalLabel">{{ __("New Chat") }}</h5>
          </div>
          <div class="modal-body">
            <select class="form-select" name="department">
              <option selected>{{ __('-- Select Department --') }}</option>
              @if (in_array('artshow', $userChatDepartments))
                <option disabled>Artshow</option>
              @else
                <option value="artshow">Artshow</option>
              @endif
              @if (in_array('dealersden', $userChatDepartments))
                <option disabled>Dealers Den</option>
              @else
                <option value="dealersden">Dealers Den</option>
              @endif
              @if (in_array('events', $userChatDepartments))
                <option disabled>Events</option>
              @else
                <option value="events">Events</option>
              @endif
            </select>
          </div>
          <div class="modal-footer" style="display: flex; justify-content: center;">
              <a class="btn btn-secondary" onclick="$('#newChatModal').modal('toggle');" data-dismiss="modal">{{ __("Close") }}</a>
              <button type="submit" class="btn btn-primary">{{ __("Create") }}</button>
          </div>
        </form>
      </div>
    </div>
</div>
