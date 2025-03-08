@extends('layouts.app')
@section('title', __("Notifications"))
@section('content')
    <div class="container">
        <x-tabs :tabs="['Notifications', 'Announcements']" :active-index="count($notifications) == 0 ? 1 : 0">
            <x-slot name="Notifications" :badge="auth()->user()->unreadNotifications->count()" badgeClass="bg-danger border border-danger-subtle">
                <div class="col-12 py-4 pt-5 d-inline-flex gap-2">
{{--                    @if(auth()->user()->unreadNotifications->count() > 0)--}}
{{--                        <form action="{{ route('notifications.update') }}" method="POST">--}}
{{--                            @method('PATCH')--}}
{{--                            @csrf--}}
{{--                            <button type="submit" class="btn btn-primary">--}}
{{--                                {{ __('Mark as read') }}--}}
{{--                            </button>--}}
{{--                        </form>--}}
{{--                    @endif--}}
                    <a href="{{ route("notifications.index", ['old' => !request()->query("old")]) }}" class="btn btn-outline-light">
                        {{ request()->query("old") ? __("Show unread notifications") :__("Show old notifications") }}
                    </a>
                </div>
                @forelse ($notifications as $notification)
                    <div @class(["card my-3 rounded-4", "opacity-75" => $notification->read_at])>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-auto d-flex align-items-center justify-content-center fs-1">
                                    <i class="bi bi-bell"></i>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        {{ $notification->view() }}
                                        <div class="col-12 text-end text-muted small align-content-end">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="container text-center m-5">
                        <p>{{ __("No new Notifications") }}</p>
                    </div>
                @endforelse
                <div class="container">
                    {{ $notifications->links() }}
                </div>
            </x-slot>

            <x-slot name="Announcements" :badge="\App\Models\Post\Post::recent()->count()" badgeClass="bg-dark border border-dark-subtle">
                <div class="pt-4">
                    <livewire:announcements />
                </div>
            </x-slot>
        </x-tabs>

    </div>
@endsection
