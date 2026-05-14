@extends('layouts.app')
@section('title', __("Dealer's Den Sign Up"))

@section('content')

    <div class="container">
        {!! \App\Services\PageHookService::resolve("dealers.create.top")  !!}
        <livewire:ddas.dealers-signup />
    </div>
@endsection
