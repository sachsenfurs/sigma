@extends('layouts.app')
@section('title', __("test"))

@section('content')

@foreach ($artshow as $artist )
<p>
{{$artist->id}}
{{$artist->name}}
<br/>
{{$artist->social}}
</p>
@endforeach
@endsection