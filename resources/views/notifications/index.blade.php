@extends('layouts.app')
@section('title', "Notifications")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-0 col-md-9"></div>
            <div class="col-12 col-md-3 d-flex flex-row-reverse">
                <div class="m-4">
                    <form action="{{ route('notifications.update') }}" method="POST">
                        @method('PATCH')
                        @csrf

                        <button type="submit" class="btn btn-primary">
                            {{ __('Mark as read') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @forelse ($notifications as $notification)
            <div class="card m-4 rounded-4">
                <div class="row">
                    <div class="col-2 text-center d-flex" style="font-size: 3rem; justify-content: center; align-items: center;">
                        <i class="bi bi-bell"></i>
                    </div>
                    <div class="col-10 p-3">
                            @switch($notification->data['type'])
                                @case('chat_new_message')
                                    <a class="btn p-2 w-75 text-left" href="{{ route('chats.index') }}?chat_id={{ $notification->data['id'] }}">
                                        <h2 class="p-none m-none">{{ __('New Chat Message') }}</h2>
                                        <p class="p-none m-none">{{ __('in the chat with the :department department', ["department" => __($notification->data['department'])]) }}</p>
                                    </a>
                                    @break
                                @case(2)
                                    <a href="{{ route("") }}">
                                        <h2 class="p-none m-none">{{ $notification->data['Title'] }}</h2>
                                        <p class="p-none m-none">{{ $notification->data['Message'] }}</p>
                                    </a>
                                    @break
                                @default
                            @endswitch
                    </div>
                </div>
            </div>
        @empty
            <div class="container" style="min-height: 50vh;">
                <p>{{ __("No new Notifications") }}</p>
            </div>
        @endforelse
    </div>
@endsection
