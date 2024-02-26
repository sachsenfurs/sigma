@extends('layouts.app')
@section('title', __('Dealer\'s Den'))

@section('content')
    <div class="container">
        <h1 class="pt-2 pb-5 text-center">
            Liste der Dealer
        </h1>

        <div class="d-flex justify-content-center p-3">
            <a href="{{ route('dealersden.create') }}" class="btn btn-primary">New Dealer</a>
        </div>

        <div class="justify-center px-md-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Dealer List</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Dealer</th>
                                <th scope="col">Infos</th>
                                <th scope="col">Social Media</th>
                                <th scope="col">Logo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dealers as $dealer)
                                @if ($dealer->approved == 1)
                                    <tr>
                                        <td>{{ $dealer->name }}</td>
                                        <td>{{ $dealer->info }}</td>
                                        <td>{{ $dealer->gallery_link }}</td>
                                        <td>{{ $dealer->icon_file }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
