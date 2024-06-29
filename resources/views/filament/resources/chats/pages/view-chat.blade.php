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
            <div class="border">
                <form method="POST" action="{{ route('chats.store', $record->id) }}">
                    @csrf
                    <div class="flex items-center px-3 py-2 rounded-lg bg-gray-50 dark:bg-gray-700">
                        <textarea name="text" rows="1" class="block mx-4 p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your message..."></textarea>
                        <button type="submit" class="inline-flex justify-center p-2 text-blue-600 rounded-full cursor-pointer hover:bg-blue-100 dark:text-blue-500 dark:hover:bg-gray-600">
                            <svg class="w-5 h-5 rotate-90 rtl:-rotate-90" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                                <path d="m17.914 18.594-8-18a1 1 0 0 0-1.828 0l-8 18a1 1 0 0 0 1.157 1.376L8 18.281V9a1 1 0 0 1 2 0v9.281l6.758 1.689a1 1 0 0 0 1.156-1.376Z"/>
                            </svg>
                            <span class="sr-only">Send message</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-filament-panels::page>
