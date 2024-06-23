@extends('layouts.app')
@section('title', 'Chats')
@section('content')
    <div class="container">
        <h1>Chats</h1>
        @if (isset($currChat))
            <div class="row mb-4 mx-md-none mx-2">
                <div class="col-8 col-md-9 p-0"></div>
                <div class="col-4 col-md-3 p-0 d-flex flex-row-reverse">
                    @if ($showNewChatButton)
                        <div>
                            <a class="btn btn-primary" onclick="$('#newChatModal').modal('toggle');" data-toggle="modal"
                                data-target="#newChatModal">+ {{ __('New Chat') }}</a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row p-0 mx-md-none mx-1" style="min-height: 60vh !important;">
                <div class="col-12 col-md-3 text-center m-0 p-0 rounded-left" style="background-color:#ffffff11;">
                    @forelse($chats AS $chat)
                        <div class="col-12 p-3">
                            <a class="btn border border-secondary rounded p-2 w-75"
                                href="{{ route('chats.index') }}?chat_id={{ $chat->id }}">
                                <p class="m-0">{{ $chat->department }}</p>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 border border-secondary p-3">
                            <p class="m-2 p-1">{{ _('No chats available') }}</p>
                        </div>
                    @endforelse
                </div>
                <div class="col-12 col-md-9 border border-secondary border-left-0 bg-dark rounded-right">
                    <div id="chat" className="card-body p-1 pt-3" style="height: 500px; min-height: 500px; max-height: 500px; overflow: auto !important;">
                        @forelse ($currChat->messages as $message)
                            @if (auth()->user()->id == $message->user_id)
                                <div class="d-flex flex-row justify-content-end mt-2">
                                    <div class="p-2 ms-3 py-3 ml-auto"
                                        style="border-radius: 15px; background-color: rgba(57, 192, 237,.2); min-width: 5vw;">
                                        <pre class="small mb-0" style="white-space: pre-wrap;">{{ $message->text }}</pre>
                                    </div>
                                    @if ($message->user->avatar)
                                        <img src="{{ $message->user->avatar }}" alt="{{ $message->user->name }}"
                                            style="width: 45px; height: 100%; margin-left: 0.5rem;">
                                    @else
                                        <i class="bi bi-person-circle"
                                            style="font-size: 45px; height: 100%; margin-left: 0.5rem;"></i>
                                    @endif
                                </div>
                                <div class="d-flex flex-row justify-content-end mb-2">
                                    <p class="text-muted ml-auto text-end" style="font-size: 0.75em;">
                                        {{ date('d.m.y - H:i', strtotime($message->created_at)) }}
                                    </p>
                                </div>
                            @else
                                <div class="d-flex flex-row justify-content-start mt-2">
                                    @if ($message->user->avatar)
                                        <img src="{{ $message->user->avatar }}" alt="{{ $message->user->name }}"
                                            style="width: 45px; height: 100%; margin-right: 0.5rem;">
                                    @else
                                        <i class="bi bi-person-circle"
                                            style="font-size: 45px; height: 100%; margin-right: 0.5rem;"></i>
                                    @endif
                                    <div class="p-2 me-3 border py-3"
                                        style="border-radius: 15px; background-color: #fbfbfb; color: #000; min-width: 5vw;">
                                        <p class="p-0 m-0">{{ $message->user->name }}</p>
                                        <pre class="small mb-0" style="white-space: pre-wrap;">{{ $message->text }}</pre>
                                    </div>
                                </div>
                                <div class="d-flex flex-row justify-content-start mb-2">
                                    <p class="text-muted ml-auto text-start" style="font-size: 0.75em;">
                                        {{ date('d.m.y - H:i', strtotime($message->created_at)) }}
                                    </p>
                                </div>
                            @endif
                            <span ref={scroll}></span>
                        @empty
                            <div class="d-flex justify-content-center mt-2">
                                <p>{{ __('No Messages') }}</p>
                            </div>
                        @endforelse
                        <div id="newMessageBanner" class="d-flex justify-content-center mt-2 rounded py-3" style="display: none !important; background-color: #ff00001f;">
                            <div>
                                <p>{{ __('A new message is available, please refresh!') }}</p>
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-danger" type="button" onClick="refreshPage()">{{ __('Refresh') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="card-footer">
                        <form method="POST" action="{{ route('chats.store', $currChat->id) }}">
                            @csrf
                            <div class="row border-top mt-3 p-2">
                                <div class="col-12 col-md-10">
                                    <textarea name="text" type="text" class="form-control"></textarea>
                                </div>
                                <div class="col-12 col-md-2 p-2">
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-primary" type="submit">{{ __('Send') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="d-flex justify-content-center mt-2 pt-5" style="height: 150px; min-height: 150px; max-height: 150px;">
            <div>
                <p>{{ __("Click the \"New Chat\" button to start a chat with a department") }}</p>
                <div class="d-flex justify-content-center">
                    <a class="btn btn-primary" onclick="$('#newChatModal').modal('toggle');" data-toggle="modal" data-target="#newChatModal">+ {{ __('New Chat') }}</a>
                </div>
            </div>
        </div>
    @endif
    <x-modal.chat-new :userChatDepartments="$currChatDepartments" />
    @if (isset($currChat))
        <script>
            // Scroll to bottom of the chat
            let scrollableDiv = document.getElementById('chat');
            scrollableDiv.scrollTop = scrollableDiv.scrollHeight;

            setInterval(function() {
                $.ajax({
                    url: '/chats/refresh/{{ $currChat->id }}',
                    method: 'GET',
                    data: {
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        console.log(data);
                        if (data.messages > {{ $currChat->messages()->count() }}) {
                            $('#newMessageBanner').show();
                            
                            // Scroll to bottom of the chat
                            let scrollableDiv = document.getElementById('chat');
                            scrollableDiv.scrollTop = scrollableDiv.scrollHeight;
                        }
                    }
                });
            }, 30000);

            // Refresh Page button function
            function refreshPage(){
                window.location.reload();
            }
        </script>
    @endif
@endsection
