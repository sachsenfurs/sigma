@extends('layouts.app')
@section('title', "Notifications")
@section('content')
    <div class="container">
      @foreach ($notifications as $notification)
        <div class="card m-4 rounded-4">
            <div class="row">
                <div class="col-2 text-center d-flex" style="font-size: 3rem; justify-content: center; align-items: center;">
                    <i class="bi bi-bell"></i>
                </div>
                <div class="col-10 p-3" style="justify-content: center; align-items: center;">
                    <p>
                        <h2 class="p-none m-none">{{ $notification->data['title'] }}</h2>
                        <p class="p-none m-none">{{ $notification->data['message'] }}</p>
                    </p>
                </div>
            </div>
        </div>
      @endforeach
    </div>
@endsection
