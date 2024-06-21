<x-filament-panels::page>
    <div class="container">
        <div class="col-12 col-md-9 border">
            <div id="chat" className="card-body p-1 pt-3" style="height: 500px; min-height: 500px; max-height: 500px; overflow: auto !important;">
                @forelse ($record->messages as $message)
                    @if (auth()->user()->id == $message->user_id)
                        <div class="flex flex-row justify-end mt-2">
                            <div class="p-2 ms-3 py-3 ml-auto" style="border-radius: 15px; background-color: rgba(57, 192, 237,.2); min-width: 5vw;">
                                <pre class="small mb-0">{{ $message->text }}</pre>
                            </div>
                            @if ($message->user->avatar)
                                <img src="{{ $message->user->avatar }}" alt="{{ $message->user->name }}" style="width: 45px; height: 100%; margin-left: 0.5rem;">
                            @else
                                <i class="bi bi-person-circle" style="font-size: 45px; height: 100%; margin-left: 0.5rem;"></i>
                            @endif
                        </div>
                        <div class="flex flex-row justify-end mb-2">
                            <p class="text-muted ml-auto text-end">
                                {{ date('d.m.y - H:i', strtotime($message->created_at)) }}
                            </p>
                        </div>
                        @else
                            <div class="flex flex-row justify-start mt-2">
                                @if ($message->user->avatar)
                                    <img src="{{ $message->user->avatar }}" alt="{{ $message->user->name }}" style="width: 45px; height: 100%; margin-right: 0.5rem;">
                                @else
                                    <i class="bi bi-person-circle" style="font-size: 45px; height: 100%; margin-right: 0.5rem;"></i>
                                @endif
                                <div class="p-2 me-3 border py-3" style="border-radius: 15px; background-color: #fbfbfb; color: #000; min-width: 5vw;">
                                    <p class="p-0 m-0">{{ $message->user->name }}</p>
                                    <pre class="small mb-0">{{ $message->text }}</pre>
                                </div>                                                  
                            </div>
                            <div class="flex flex-row justify-start mb-2">
                                <p class="text-muted ml-auto text-start">
                                    {{ date('d.m.y - H:i', strtotime($message->created_at)) }}
                                </p>
                            </div>
                        @endif
                @empty
                    <div class="flex justify-center mt-2">
                        <p>{{ __('No Messages')}}</p>
                    </div>
                @endforelse
            </div>
            <div className="border">
                <form method="POST" action="{{ route('chats.store', $record->id) }}">
                    @csrf
                    <div class="grid grid-rows-1 border-top mt-3 p-2">
                        <div class="columns-12 columns-10md">
                            <textarea name="text" type="text" class="form-control"></textarea>
                        </div>
                        <div class="columns-12 columns-2md p-2">
                            <div class="d-flex justify-content-center">
                                <button class="" type="submit">{{ __("Send")}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-filament-panels::page>
