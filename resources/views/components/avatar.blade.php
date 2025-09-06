@props([
    'user' => null,
    'size' => '2rem',
])
@if($user?->avatar_thumb)
    <img src="{{ $user->avatar_thumb }}" alt="" class="rounded-circle imsg-thumbnail" style="height: {{$size}}" lazy>
@else
    <span class="badge rounded-circle text-bg-secondary" style="width:{{$size}};height:{{$size}};display:inline-flex;align-items:center;justify-content:center">
        {{ substr($user->name, 0, 1) }}
    </span>
@endif
