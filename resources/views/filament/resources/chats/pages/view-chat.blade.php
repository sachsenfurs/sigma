<x-filament-widgets::widget>
    <div class="w-full">
        <div id="chat" class="overflow-scroll scrolldown" style="max-height: 40rem" x-init="$dispatch('scrolldown')">
            <div class="mx-auto space-y-4" wire:poll.10s>
                @forelse ($record->messages as $message)
                    @php($own = $message->isOwn())
                    <div @class(["flex items-end gap-3", "justify-end" => $own])>
                        @if ($message->user->avatar)
                            <img src="{{ $message->user->avatar_thumb }}" @class(["w-10 h-10 rounded-full object-cover", $own ? "order-3" : "order-1"]) alt="{{ $message->user->name }}">
                        @else
                            <i class="w-10 h-10 rounded-full object-cover"></i>
                        @endif
                        <div class="order-2">
                            <div @class([
                                    "p-3 rounded-lg shadow-md max-w-7xl min-w-48 break-all",
                                    $own ? "bg-blue-500" : "bg-gray-100 text-gray-800",
                                    "border-l-4 border-red-600" => !$own AND $message->read_at == null
                            ])>
                                {!! nl2br(e($message->text)) !!}
                            </div>
                            <div @class(["text-xs text-gray-500 mt-1 flex items-center", "text-right" => $own])>
                                @if($own)
                                    {{ $message->user->name }} -
                                @endif
                                {{ $message->created_at->diffForHumans() }}
                                @if($own AND $message->read_at)
                                    <span class="inline-block" title="{{ __("Read at :time", ['time' => $message->created_at->format("d.m.Y, H:i")]) }}">
                                        <svg class="size-5 mx-1 text-cyan-500 stroke-current" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" fill="none">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17l5 5 12-12M16 20l2 2 12-12"/>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    {{ __("No Messages") }}
                @endforelse

            </div>
        </div>
        <div class="flex items-center p-2 m-2 rounded-lg bg-gray-50 dark:bg-gray-700">
                <textarea wire:model="text" name="text" rows="6"
                          wire:keydown.ctrl.enter="sendMessage"
                          class="block mx-4 p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                          placeholder="{{ __("Your message") }}" ></textarea>
            <button type="submit" class="inline-flex justify-center p-2 text-blue-600 rounded-full cursor-pointer hover:bg-blue-100 dark:text-blue-500 dark:hover:bg-gray-600" wire:click="sendMessage">
                <svg class="w-5 h-5 rotate-90 rtl:-rotate-90" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                    <path d="m17.914 18.594-8-18a1 1 0 0 0-1.828 0l-8 18a1 1 0 0 0 1.157 1.376L8 18.281V9a1 1 0 0 1 2 0v9.281l6.758 1.689a1 1 0 0 0 1.156-1.376Z"/>
                </svg>
                <span class="sr-only">{{ __("Send") }}</span>
            </button>
        </div>
    </div>
</x-filament-widgets::widget>

@script
<script>
    $wire.on("scrolldown", function() {
        window.setTimeout(function() {
            document.querySelectorAll(".scrolldown").forEach((el) => el.scrollTo(0, el.scrollHeight));
        }, 100);
    });
</script>
@endscript
