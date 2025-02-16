<div class="col-12">
    <strong>
        {{ $notification->type::getName() }}
    </strong>
    @foreach($notification->data['lines'] ?? [] AS $line)
        <p>
            {{ $line }}
        </p>
    @endforeach
    @isset($notification->data['action_url'])
        <a href="{{ $notification->data['action_url'] }}" class="stretched-link text-decoration-none btn btn-outline-secondary">{{ $notification->data['action'] ?? "" }}</a>
    @endif
</div>
