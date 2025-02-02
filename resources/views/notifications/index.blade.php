@extends('layouts.app')
@section('title', "Notifications")
@section('content')
    <div class="container">
        <x-tabs :tabs="['Notifications', 'Announcements']" :active-index="count($notifications) == 0 ? 1 : 0">
            <x-slot name="Notifications" :badge="auth()->user()->unreadNotifications->count()" badgeClass="bg-danger border border-danger-subtle">
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <div class="row">
                        <div class="col-0 col-md-9"></div>
                        <div class="col-12 col-md-3 d-flex flex-row justify-content-center justify-content-md-end">
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
                @endif
                @forelse ($notifications as $notification)
                    <div class="card my-3 rounded-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-auto d-flex align-items-center justify-content-center fs-1">
                                    <i class="bi bi-bell"></i>
                                </div>
                                <div class="col">
                                    {{ $notification->view() }}
                                    <div class="col-12 text-end text-muted small">
                                        {{ $notification->created_at->diffForHumans() }}
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
            </x-slot>

            <x-slot name="Announcements" :badge="\App\Models\Post\Post::recent()->count()" badgeClass="bg-dark border border-dark-subtle">
                <div class="pt-4">
                    <livewire:announcements />
                </div>
            </x-slot>
        </x-tabs>

    </div>
@endsection
