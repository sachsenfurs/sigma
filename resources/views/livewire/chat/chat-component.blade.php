<div>
    <div class="row border border-secondary rounded overflow-hidden m-0" style="height: calc(100vh - 100px)">
        <ul class="col-2 col-sm-3 col-md-4 text-center p-0 list-group list-group-flush overflow-scroll border-2 bg-dark-subtle" style="height: inherit">
            @if(count($departments) > 0)
                @can("create", \App\Models\Chat::class)
                    <button class="list-group-item list-group-item-action text-primary fs-5 p-3" wire:click="newChatModal()">
                        <i class="bi bi-plus icon-link"></i>
                        <span class="d-none d-sm-block">
                            {{ __('New Chat') }}
                        </span>
                    </button>
                @endcan
            @endif

            @forelse($chats AS $chat)
                <button @class(["list-group-item list-group-item-action d-flex text-start align-items-baseline", "active" => $chat->id == $currentChat?->id])
                        wire:click="selectChat({{$chat->id}})"
                        aria-current="{{ $chat->id == $currentChat?->id ? "true":"false" }}">
                    <div class="me-auto text-break">
                        <div class="fw-bold d-none d-md-block">{{ __($chat->userRole->name_localized) }}</div>
                        <span class="d-none d-md-block">
                            {{ $chat->subject }}
                        </span>
                        <span class="d-md-none small">
                            {{ \Illuminate\Support\Str::limit($chat->subject, 10) }}
                        </span>
                    </div>
                    <span @class(["badge rounded-pill",  $chat->id == $currentChat?->id  ? "text-bg-danger" : "text-bg-primary"])>{{ $chat->unread_messages_count ?: "" }}</span>
                </button>
            @empty
                <p class="m-2 p-1">{{ __('No chats available') }}</p>
            @endforelse
        </ul>

        <div class="col d-flex flex-column p-0 border-start border-2" style="height: inherit;"  @if($currentChat?->unread_messages_count) wire:click="markAsRead" @endif>
            @if($subjectable = $currentChat?->subjectable)
                <div class="bg-dark-subtle w-100 p-3">
                    @if($subjectable instanceof \App\Models\SigEvent)
                        <a href="{{ route("sigs.index") }}" class="text-decoration-none">
                            {{ __("SIG") }}: {{ $subjectable->name_localized }}
                            @foreach($subjectable->languages AS $lang)
                                <x-flag :language="$lang" />
                            @endforeach
                            <span class="badge bg-dark fs-6">{{ $subjectable->duration_hours }} h</span>
                            <span @class(['badge d-inline-block fw-normal p-2 text-uppercase', $subjectable->approval->style()])>
                                {{ $subjectable->approval->name() }}
                            </span>
                        </a>
                    @endif

                    @if($subjectable instanceof \App\Models\Ddas\ArtshowItem)
                        <a href="{{ route("artshow.create") }}" class="text-decoration-none">
                            <div class="row">
                                <div class="col-auto align-content-center">
                                    <img src="{{ $subjectable->image_url }}" class="img-fluid img-thumbnail" style="height: 3.5em">
                                </div>
                                <div class="col align-content-center">
                                    {{ __("Item") }}: {{ $subjectable->name }}
                                    <span class="badge bg-dark fs-6">{{ $subjectable->starting_bid }} &euro;</span>
                                    <span @class(['badge d-inline-block fw-normal p-2 text-uppercase', $subjectable->approval->style()])>
                                        {{ $subjectable->approval->name() }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endif

                    @if($subjectable instanceof \App\Models\Ddas\Dealer)
                        <a href="{{ route("dealers.create") }}" class="text-decoration-none">
                            <div class="row">
                                <div class="col-auto align-content-center">
                                    @if($subjectable->icon_file_url)
                                        <img src="{{ $subjectable->icon_file_url }}" class="img-fluid img-thumbnail" style="height: 3.5em">
                                    @endif
                                </div>
                                <div class="col align-content-center">
                                    {{ __("Dealer") }}: {{ $subjectable->name }}
                                    <span @class(['badge d-inline-block fw-normal p-2 text-uppercase', $subjectable->approval->style()])>
                                        {{ $subjectable->approval->name() }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
            @endif
            <div class="overflow-x-hidden overflow-y-scroll px-3 scrolldown" style="min-height: 80%; word-break: break-all;" wire:poll.10s>
                @php($newMessage=false)
                @forelse ($currentChat?->messages->load("user") ?? [] as $message)
                    @php($own = $message->user_id == auth()->id())
                    @if((!$message->read_at OR $message->read_at->diffInMinutes() < 3) AND !$own AND !$newMessage)
                        <div class="col-12 text-center" x-init="$dispatch('scrolldown')">{{ __("New Messages") }}</div>
                        <hr>
                        @php($newMessage=true)
                    @endif

                    <div @class(["row mt-2", "justify-content-end text-end" => $own])>
                        <div @class(["col-auto p-1 ml-auto rounded order-2", $own ? "bg-primary text-white" : "bg-white text-dark"]) style="min-width: 10rem">
                            <div class="p-2 mb-0 text-wrap text-start">
                                {!! nl2br(e($message->text)) !!}
                            </div>
                        </div>
                        <div @class(["col-auto align-content-end pb-1", "order-3" => $own])>
                            @if ($message->user->avatar_thumb)
                                <img src="{{ $message->user->avatar_thumb }}" class="img-fluid img-thumbnail rounded-circle" style="width: 3em" alt="{{ $message->user->name }}">
                            @else
                                <i class="bi bi-person-circle fs-2"></i>
                            @endif
                        </div>
                        <div class="col-12 order-4">
                            <p class="text-muted ml-auto small" title="{{ __("Sent:") . " ". $message->created_at->toDateTimeString() }}">
                                @if(!$own)
                                    {{ $message->user->name }} -
                                @endif
                                {{ $message->created_at->diffForHumans() }}
                                @if($own)
                                    <i @class(["bi", $message->read_at ?  "bi-check2-all text-info" : "bi-check2"])></i>
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="d-flex justify-content-center align-items-center h-100 p-3">
                        @if($currentChat)
                            <i class="text-muted">{{ __('Chat opened. Please enter your message below.') }}</i>
                        @else
                            <p>{{ __('Please select a chat') }}</p>
                        @endif
                    </div>
                @endforelse
            </div>
            @if($currentChat AND $currentChat->status != \App\Enums\ChatStatus::LOCKED)
                <div class="d-flex flex-nowrap flex-grow m-0" style="height: inherit">
                    <div class="col d-flex" style="flex-direction: column">
                        <x-form.livewire-input :placeholder="__('Your message')" type="textarea" name="text" maxlength="4000"
                                               class="small flex-grow-1 rounded-0 border-0 border-top" style="resize: none"
                                               wire:loading.attr="disabled" wire:target="submitMessage" wire:model="text" wire:keydown.ctrl.enter="submitMessage" />
                    </div>
                    <div class="col-auto d-grid">
                        <button class="btn btn-secondary rounded-0" wire:click="submitMessage" wire:loading.class="disabled" wire:target="submitMessage">
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
            <div class="col-12 text-muted">
                {{ __("You can start a new chat with our team here if you have a question or a problem. Please select the department that could help you with your request.") }}
            </div>
            <div class="col-12">
                <label for="department" class="form-label">{{ __("Department") }}</label>
                <select @class(['form-select', 'border-danger' => $errors->has("department")]) id="department" name="department" wire:model="department">
                    <option value="0" selected>{{ __('-- Select Department --') }}</option>
                    @foreach ($departments as $dep)
                        <option value="{{ $dep->id }}">{{ $dep->name_localized }}</option>
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
