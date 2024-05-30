@extends('layouts.app')
@section('title', "Chats")
@section('content')
    <div class="container">
        <h1>Chats</h1>
        <div class="row m-5">
            <div class="col-9"></div>
            <div class="col-3 align-items-end">
                <a class="btn-primary" href="">+ New Chat</a>
            </div>
        <div class="row border border-secondary rounded" style="min-height: 60vh !important;">
            <div class="col-3 text-center">
                @forelse($chats AS $chat)
                    <div class="col-12">
                        <h2>$chat->name;</h2>
                    </div>
                @empty
                    <p class="m-2">No chats available</p>
                @endforelse
            </div>
            <div class="col-9 border-secondary" style="border-left: 1px solid !important;">

            </div>
        </div>
    </div>
@endsection
