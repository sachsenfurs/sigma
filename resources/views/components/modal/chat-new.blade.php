@props([
  'departments',
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
            <select class="form-select" name="departmentId">
              <option selected>{{ __('-- Select Department --') }}</option>
              @foreach ($departments as $dep)
                  @if (!in_array($dep->id, $userChatDepartments))
                    <option value="{{ $dep->id }}">{{ $dep->title }}</option>
                  @else
                    <option disabled>{{ $dep->title }}</option>
                  @endif
              @endforeach
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
