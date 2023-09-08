@extends('layouts.app')
@section('title', __("SF Post"))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
                <div class="text-center p-4">
                        <a href="{{ route("posts.create") }}" class="btn btn-primary">Neue Ankündigung im Infochannel</a>
                </div>
                <div class="card">
                    <div class="card-header">@lang("Post History")</div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Datum</th>
                                <th>Text DE</th>
                                <th>Text EN</th>
                                <th>Bild</th>
                                <th>User</th>
                                <th>Actions</th>
                            </tr>

                            @forelse($posts AS $post)
                                <tr>
                                    <td>{{ $post->created_at->diffForHumans() }}</td>
                                    <td>{{ $post->text_de }}</td>
                                    <td>{{ $post->text_en }}</td>
                                    <td>
                                        @if($post->image)
                                            <img src="{{ Storage::url($post->image) }}" alt="" class="object-fit-cover" style="max-height: 300px;max-width: 300px;">
                                        @endif
                                    </td>
                                    <td>{{ $post->user->name ?? "-deleted-" }}</td>
                                    <td>
                                        <form method="POST" action="{{ route("posts.destroy", $post) }}">
                                            @method("DELETE")
                                            @csrf
                                            <input type="submit" class="btn btn-danger" value="Delete">
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100">Ziemlich leer hier</td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
