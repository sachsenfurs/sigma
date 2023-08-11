@extends('layouts.app')
@section('title', 'Program')
{{--<style>--}}
{{--    ul.nav {--}}
{{--        display: inline-block;--}}
{{--        white-space: nowrap;--}}
{{--    }--}}

{{--    table {--}}
{{--        margin: 0;--}}
{{--        padding: 0;--}}
{{--        width: calc(100% - 2px);--}}
{{--    }--}}

{{--    table,--}}
{{--    tr,--}}
{{--    td {--}}
{{--        box-sizing: border-box;--}}
{{--    }--}}

{{--    td.event {--}}
{{--        border: 2px solid #000;--}}
{{--        text-align: center;--}}
{{--        vertical-align: middle;--}}
{{--        font-size: 1.4em;--}}
{{--        color: #eee;--}}
{{--        cursor: pointer;--}}
{{--    }--}}

{{--    /* Color Official East Event */--}}
{{--    td.oee {--}}
{{--        background: #ffbf90;--}}
{{--    }--}}

{{--    /* Color Fursuitevents/Fursuitrelevantes */--}}
{{--    td.fe {--}}
{{--        background: #ffc133;--}}
{{--    }--}}

{{--    /* Color Gamingevents/GameShow/Compatition */--}}
{{--    td.grsswk {--}}
{{--        background: #fee744;--}}
{{--    }--}}

{{--    /* Color GuestSigs/Workshops */--}}
{{--    td.gswssa {--}}
{{--        background: #fd92c0;--}}
{{--    }--}}

{{--    /* Color Artshow */--}}
{{--    td.as {--}}
{{--        background: #30c1b6;--}}
{{--    }--}}

{{--    /* Color Con-Ops */--}}
{{--    td.lsco {--}}
{{--        background: #ff441c;--}}
{{--    }--}}

{{--    /* Color Registration */--}}
{{--    td.reg {--}}
{{--        background: #1c2694;--}}
{{--    }--}}

{{--    td a {--}}
{{--        text-decoration: underline;--}}
{{--        box-sizing: content-box;--}}
{{--        display: block;--}}
{{--        color: #fff;--}}
{{--    }--}}

{{--    td a:hover {--}}
{{--        text-decoration: none;--}}
{{--        color: #cccccc;--}}
{{--    }--}}

{{--    tr.active {--}}
{{--        background: #061557;--}}
{{--    }--}}

{{--    div.activ {--}}
{{--        background: #b35050;--}}
{{--    }--}}

{{--    tr.weekday {--}}
{{--        background: #cccccc;--}}
{{--        font-size: 22px;--}}
{{--    }--}}

{{--    strong.weekday {--}}
{{--        background: #cccccc;--}}
{{--        font-size: 22px;--}}
{{--        padding-left: 1rem;--}}
{{--        padding-right: 1rem;--}}
{{--    }--}}

{{--    div.test {--}}
{{--        display: flex;--}}
{{--        color: #0b7437;--}}
{{--        font-size: 15px;--}}
{{--        font-weight: 800;--}}
{{--    }--}}

{{--    div.location {--}}
{{--        width: calc(100% - 2px);--}}
{{--    }--}}

{{--    div.time {--}}
{{--        width: calc(50% - 1px);--}}
{{--    }--}}

{{--    div.scrollmenu {--}}
{{--        background-color: #eee;--}}
{{--        overflow-x: auto;--}}
{{--        overflow-y: hidden;--}}
{{--    }--}}

{{--    div.scrollmenu li {--}}
{{--        display: inline-flex;--}}
{{--        color: white;--}}
{{--        text-align: center;--}}
{{--        text-decoration: none;--}}
{{--    }--}}

{{--    div.scrollmenu a:hover {--}}
{{--        background-color: #777;--}}
{{--    }--}}
{{--</style>--}}
<!-- -->
<!-- Looking for an API? Ask @Kidran! -->
<!-- -->
@section('content')
    <div class="container">


        <div class="mt-4">
            <!-- Day Nav Tabs -->
            <div class="scrollmenu">
                <ul class="nav nav-tabs">
                    @foreach ($days as $index => $day)
                        <li class="nav-item">
                            <a class="nav-link{{ $loop->first ? ' active' : '' }}" data-bs-toggle="tab"
                                href="#ConDay{{ $index + 1 }}">
                                {{ Str::upper(\Illuminate\Support\Carbon::parse($day)->locale('en')->dayName) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Tab panes /Content -->
            @foreach($entries AS $entry)
                <div class="card mt-3">
                    <div class="row g-0 flex-nowrap d-flex">
                        <div class="col-lg-2 col-4 d-flex">
                            <div class="card-body align-self-center text-center">
                                <h2>{{ $entry->start->format("H:i") }}</h2>
                                <h5 class="text-muted">{{ $entry->formatted_length }}</h5>
                            </div>
                        </div>
                        <div class="col-lg-9 col-6 d-flex">
                            <div class="card-body align-self-center">
                                <h1><a href="{{ route("public.timeslot-show", $entry) }}" class="text-decoration-none">{{ $entry->sigEvent->name }}</a></h1>
                                <p class="card-text">
                                    <i class="bi bi-person-circle"></i> {{ $entry->sigEvent->sigHost->name }}
                                </p>
                                <p>
                                    <i class="bi bi-geo-alt"></i> {{ $entry->sigLocation->name }}
                                </p>
                            </div>
                        </div>
                        <div class="card-body col-lg-1 col-2 d-flex">
                            <a type="button" class="fav-btn text-secondary align-self-center w-100 text-end" data-event="{{ $entry->id }}">
                                <i class="bi bi-heart" style="font-size: 2em"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
        {{-- <table class="table">
        @foreach ($days as $day)
        <tr></tr>
            <tr class="weekday">
                <td colspan="{{ count($locations) + 1 }}">
                    <strong>{{ $day }} -
                        {{ Str::upper(\Illuminate\Support\Carbon::parse($day)->dayName) . ' | ' . Str::upper(\Illuminate\Support\Carbon::parse($day)->locale('en')->dayName) }}</strong>
                </td>
            </tr>
            <tr>
                <th></th>
                @foreach ($locations as $location)
                <th>
                        {{ $location->name }}
                    </th>
@endforeach
            </tr>
            @php
                $rowspan = [];
            @endphp
            @for ($y = 0; $y <= 32; $y++)
@php
    $currentTime = \Illuminate\Support\Carbon::parse($day)
        ->setHours(8)
        ->setMinutes(0)
        ->addMinutes(30 * $y);
@endphp
                <tr
                    class="{{ $currentTime > \Illuminate\Support\Carbon::now() && $currentTime < \Illuminate\Support\Carbon::now()->addMinutes(30) ? 'active' : '' }}">
                    <td>{{ $currentTime->format('H:i') }}</td>

                    @php
                        for ($x = 0; $x < count($locations); $x++) {
                            if (isset($rowspan[$x][$y])) {
                                continue;
                            }

                            $events = $entries->filter(function ($value, $key) use ($locations, $currentTime, $x, $y) {
                                return $value->start <= $currentTime && $value->end > $currentTime && ($value->sigLocation->name ?? '') == $locations[$x]->name;
                            });
                            if ($events->first() && $events->first()->sigEvent) {
                                $sig = $events->first()->sigEvent;
                                $rows = $events->first()->duration / 30;

                                if ($rows + $y > 32) {
                                    $rows = 34 - $y;
                                }
                                if ($sig->sig_location_id == '1' || $sig->sig_location_id == '8' || $sig->sig_location_id == '15' || $sig->sig_location_id == '22') {
                                    echo '<td rowspan="' . $rows . '" class="event oee">';
                                } elseif ($sig->sig_location_id == '2' || $sig->sig_location_id == '9' || $sig->sig_location_id == '16' || $sig->sig_location_id == '23') {
                                    echo '<td rowspan="' . $rows . '" class="event fe">';
                                } elseif ($sig->sig_location_id == '3' || $sig->sig_location_id == '10' || $sig->sig_location_id == '17' || $sig->sig_location_id == '24') {
                                    echo '<td rowspan="' . $rows . '" class="event grsswk">';
                                } elseif ($sig->sig_location_id == '4' || $sig->sig_location_id == '11' || $sig->sig_location_id == '18' || $sig->sig_location_id == '25') {
                                    echo '<td rowspan="' . $rows . '" class="event gswssa">';
                                } elseif ($sig->sig_location_id == '5' || $sig->sig_location_id == '12' || $sig->sig_location_id == '19' || $sig->sig_location_id == '26') {
                                    echo '<td rowspan="' . $rows . '" class="event as">';
                                } elseif ($sig->sig_location_id == '6' || $sig->sig_location_id == '13' || $sig->sig_location_id == '20' || $sig->sig_location_id == '27') {
                                    echo '<td rowspan="' . $rows . '" class="event lsco">';
                                } elseif ($sig->sig_location_id == '7' || $sig->sig_location_id == '14' || $sig->sig_location_id == '21' || $sig->sig_location_id == '28') {
                                    echo '<td rowspan="' . $rows . '" class="event reg">';
                                } else {
                                    echo '<td rowspan="' . $rows . '" class="event">';
                                }
                                echo '<a href="' . route('public.timeslot-show', $events->first()) . '">';
                                echo $sig->name;
                                echo '</a>';
                                echo '</td>';
                                for ($i = 0; $i < $rows; $i++) {
                                    $rowspan[$x][$y + $i] = true;
                                }
                            } else {
                                echo '<td></td>';
                            }
                        }
                    @endphp
                </tr>
@endfor
@endforeach
    </table>
</body> --}}

{{-- <ul class="nav nav-tabs">
    @foreach ($locations as $location)
        <li class="nav-item">
            <a class="nav-link{{ $loop->first ? ' active' : '' }}" data-bs-toggle="tab" href="#{{ $location->name }}">
                {{ $location->name }}
            </a>
        </li>
    @endforeach
</ul>

<div class="tab-content">
    @foreach ($locations as $location)
        <div class="tab-pane{{ $loop->first ? ' active' : '' }}" id="{{ $location->id }}">
            {{ $location->id }}
        </div>
    @endforeach
</div>

<ul class="nav nav-tabs">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab"
            href="#hrs">{{ $locations[0]->name }}</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
            href="#niedersachsen">{{ $locations[1]->name }}</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
            href="#saarland">{{ $locations[2]->name }}</a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="hrs">1</div>
    <div class="tab-pane" id="niedersachsen">2</div>
    <div class="tab-pane" id="saarland">3</div>
</div> --}}
{{-- <table class="table">
    <tr>
        <th></th>
        @foreach ($locations as $location)
            <th>
                {{ $location->name }}
            </th>
        @endforeach
    </tr>
    @php
        $rowspan = [];
    @endphp
    @for ($y = 0; $y <= 32; $y++)
        @php
            $currentTime = \Illuminate\Support\Carbon::parse($days[0])
                ->setHours(8)
                ->setMinutes(0)
                ->addMinutes(30 * $y);
        @endphp
        <tr
            class="{{ $currentTime > \Illuminate\Support\Carbon::now() && $currentTime < \Illuminate\Support\Carbon::now()->addMinutes(30) ? 'active' : '' }}">
            <td>{{ $currentTime->format('H:i') }}</td>

            @php
                for ($x = 0; $x < count($locations); $x++) {
                    if (isset($rowspan[$x][$y])) {
                        continue;
                    }

                    $events = $entries->filter(function ($value, $key) use ($locations, $currentTime, $x, $y) {
                        return $value->start <= $currentTime && $value->end > $currentTime && ($value->sigLocation->name ?? '') == $locations[$x]->name;
                    });
                    if ($events->first() && $events->first()->sigEvent) {
                        $sig = $events->first()->sigEvent;
                        $rows = $events->first()->duration / 30;

                        if ($rows + $y > 32) {
                            $rows = 34 - $y;
                        }
                        if ($sig->sig_location_id == '1' || $sig->sig_location_id == '8' || $sig->sig_location_id == '15' || $sig->sig_location_id == '22') {
                            echo '<td rowspan="' . $rows . '" class="event oee">';
                        } elseif ($sig->sig_location_id == '2' || $sig->sig_location_id == '9' || $sig->sig_location_id == '16' || $sig->sig_location_id == '23') {
                            echo '<td rowspan="' . $rows . '" class="event fe">';
                        } elseif ($sig->sig_location_id == '3' || $sig->sig_location_id == '10' || $sig->sig_location_id == '17' || $sig->sig_location_id == '24') {
                            echo '<td rowspan="' . $rows . '" class="event grsswk">';
                        } elseif ($sig->sig_location_id == '4' || $sig->sig_location_id == '11' || $sig->sig_location_id == '18' || $sig->sig_location_id == '25') {
                            echo '<td rowspan="' . $rows . '" class="event gswssa">';
                        } elseif ($sig->sig_location_id == '5' || $sig->sig_location_id == '12' || $sig->sig_location_id == '19' || $sig->sig_location_id == '26') {
                            echo '<td rowspan="' . $rows . '" class="event as">';
                        } elseif ($sig->sig_location_id == '6' || $sig->sig_location_id == '13' || $sig->sig_location_id == '20' || $sig->sig_location_id == '27') {
                            echo '<td rowspan="' . $rows . '" class="event lsco">';
                        } elseif ($sig->sig_location_id == '7' || $sig->sig_location_id == '14' || $sig->sig_location_id == '21') {
                            echo '<td rowspan="' . $rows . '" class="event reg">';
                        } else {
                            echo '<td rowspan="' . $rows . '" class="event">';
                        }
                        echo '<a href="' . route('public.timeslot-show', $events->first()) . '">';
                        echo $sig->name;
                        echo '</a>';
                        echo '</td>';
                        for ($i = 0; $i < $rows; $i++) {
                            $rowspan[$x][$y + $i] = true;
                        }
                    } else {
                        echo '<td></td>';
                    }
                }
            @endphp
        </tr>
    @endfor
</table> --}}

    {{--<script>--}}
    {{--    $('.fav-btn').click(function () {--}}
    {{--        let eventID = $(this).data('event');--}}

    {{--        $.ajax({--}}
    {{--            url: {{ route('favorites.store') }},--}}
    {{--            method: 'POST',--}}
    {{--            data: {--}}
    {{--                _token:'<?php echo csrf_token(); ?>',--}}
    {{--                timetable_entry_id:eventID--}}
    {{--            },--}}
    {{--            dataType: 'JSON',--}}
    {{--            success: function(data) {--}}
    {{--               $('#fav-' . eventID).removeClass('bi-heart').addClass('bi-heart-fill');--}}
    {{--            }--}}
    {{--            // Eine schöne Vue bauen ;D--}}
    {{--        });--}}
    {{--    });--}}
    {{--</script>--}}
@endsection
