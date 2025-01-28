<div>
    <div class="row border border-secondary rounded overflow-hidden m-0" style="height: calc(100vh - 100px)">
        <ul class="col-2 col-sm-3 col-md-4 text-center p-0 list-group list-group-flush overflow-scroll border-2 bg-dark-subtle" style="height: inherit">
            @can("create", \App\Models\Chat::class)
                <button class="list-group-item list-group-item-action text-primary fs-5 p-3" wire:click="newChatModal()">
                    <i class="bi bi-plus icon-link"></i>
                    <span class="d-none d-sm-block">
                        {{ __('New Chat') }}
                    </span>
                </button>
            @endcan

            @forelse($chats AS $chat)
                <button @class(["list-group-item list-group-item-action d-flex text-start align-items-baseline", "active" => $chat->id == $currentChat?->id])
                        wire:click="selectChat({{$chat->id}})"
                        aria-current="{{ $chat->id == $currentChat?->id ? "true":"false" }}">
                    <div class="me-auto text-break">
                        <div class="fw-bold d-none d-md-block">{{ __($chat->userRole->title) }}</div>
                        <span class="d-none d-md-block">
                            {{ $chat->subject }}
                        </span>
                        <span class="d-md-none">
                            {{ \Illuminate\Support\Str::limit($chat->subject, 10) }}
                        </span>
                    </div>
                    @if($chat->id != $currentChat?->id)
                        <span class="badge text-bg-primary rounded-pill">{{ $chat->unread_messages_count ?: "" }}</span>
                    @endif
                </button>
            @empty
                <p class="m-2 p-1">{{ __('No chats available') }}</p>
            @endforelse
        </ul>

        <div class="col d-flex flex-column p-0 border-start border-2" style="height: inherit; ">
            <div class="overflow-x-hidden overflow-y-scroll px-3 scrolldown" style="min-height: 80%">
                @php($newMessage=false)
                @forelse ($currentChat?->messages ?? [] as $message)
                    @if(!$message->read_at AND $message->user_id != auth()->id() AND !$newMessage)
                        <div class="col-12 text-center">{{ __("New Messages") }}</div>
                        <hr>
                        @php($newMessage=true)
                    @endif

                    <div @class(["row mt-2", "justify-content-end text-end" => $message->user_id == auth()->id()])>
                        <div class="col p-1 ml-auto rounded bg-dark-subtle order-2">
                            <pre class="small p-2 mb-0 text-wrap text-start">
                                {!! nl2br(e($message->text)) !!}
                            </pre>
                        </div>
                        <div @class(["col-auto align-content-end pb-1", "order-3" => $message->user_id == auth()->id()])>
                            @if ($message->user->avatar_thumb)
                                <img src="{{ $message->user->avatar_thumb }}" class="img-fluid img-thumbnail rounded-circle" style="width: 3em" alt="{{ $message->user->name }}">
                            @else
                                <i class="bi bi-person-circle fs-2"></i>
                            @endif
                        </div>
                        <div class="col-12 order-4">
                            <p class="text-muted ml-auto small">
                                @if($message->user_id != auth()->id())
                                    {{ $message->user->name }} -
                                @endif
                                {{ $message->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    @if(!$currentChat)
                        <div class="d-flex justify-content-center mt-2">
                            <p>{{ __('Please select a chat') }}</p>
                        </div>
                    @endif
                @endforelse
            </div>
            @if($currentChat)
                <div class="d-flex flex-nowrap flex-grow m-0" style="height: inherit">
                    <div class="col d-flex" style="flex-direction: column">
                        <x-form.livewire-input :placeholder="__('Your Message')" type="textarea"
                                               class="small flex-grow-1 rounded-0 border-0 border-top" style="resize: none"
                                               wire:loading.attr="disabled" wire:target="submitMessage" wire:model="text" name="text" />
                    </div>
                    <div class="col-auto d-grid">
                        <button class="btn btn-primary rounded-0" wire:click="submitMessage" wire:loading.class="disabled" wire:target="submitMessage">
                            <span wire:loading.remove wire:target="submitMessage">
                                <i class="bi bi-send icon-link"></i>
                                {{ __('Send') }}
                            </span>
                            <div class="spinner-border" wire:loading wire:target="submitMessage"></div>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <x-modal.livewire-modal action="createNewChat">
        <x-slot:title>
            {{ __("New Chat") }}
        </x-slot:title>
        <div class="row g-3">
            <div class="col-12">
                <label for="department" class="form-label">{{ __("Department") }}</label>
                <select class="form-select" name="department" wire:model="department">
                    <option value="0" selected>{{ __('-- Select Department --') }}</option>
                    @foreach (\App\Models\UserRole::chattable()->get() as $dep)
                        <option value="{{ $dep->id }}">{{ $dep->title }}</option>
                    @endforeach
                </select>
                <x-form.input-error name="department" />
            </div>
            <div class="col-12">
                <x-form.livewire-input name="subject" maxlength="40" :label="__('Subject')" required></x-form.livewire-input>
            </div>
        </div>
    </x-modal.livewire-modal>

    @script
    <script>
        $wire.on("scrolldown", function() {
            window.setTimeout(function() {
                document.querySelectorAll(".scrolldown").forEach((el) => el.scrollTo(0, el.scrollHeight));
            }, 100);
        });
    </script>
    @endscript
</div>
