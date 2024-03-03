@extends('layouts.app')
@section('title', __('Art Show'))

@section('content')
    <div class="container">
        <h2 class="pt-2 pb-5 text-center">
            Art Show Item List
        </h2>
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">
                    Item List
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Artist</th>
                            <th>Item</th>
                            <th>Description</th>
                            <th>Start Bid</th>
                            <th>Charity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($as_artists as $artist)
                            @foreach ($as_items as $item)
                                <tr>
                                    @if ($item->artshow_artist_id == $artist->id)
                                        <td>{{ $artist->name }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->starting_bid }}</td>
                                        <td>{{ $item->charity_percentage }}%</td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
