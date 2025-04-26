<div class="col-12">
    <p>
        <strong>
            {{ $notification->data['subject'] ?? $notification->type::getName() }}
        </strong>
    </p>
    @foreach($notification->data['lines'] ?? [] AS $line)
        <p>
            {{ $line }}
        </p>
    @endforeach
    @if(!empty($notification->data['action_url']))
        <a href="{{ $notification->data['action_url'] }}" class="stretched-link text-decoration-none btn btn-outline-secondary">{{ $notification->data['action'] ?? "" }}</a>
    @endif
</div>
