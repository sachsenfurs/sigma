@extends('layouts.app')
@section('title', 'Program')
@include('layouts.head')
<style>
    ul.nav {
        display: inline-block;
        white-space: nowrap;
    }y

    table {
        margin: 0;
        padding: 0;
        width: calc(100% - 2px);
    }

    table,
    tr,
    td {
        box-sizing: border-box;
    }

    td.event {
        border: 2px solid #000;
        text-align: center;
        vertical-align: middle;
        font-size: 1.4em;
        color: #eee;
        cursor: pointer;
    }

    /* Color Official East Event */
    td.oee {
        background: #ffbf90;
    }

    /* Color Fursuitevents/Fursuitrelevantes */
    td.fe {
        background: #ffc133;
    }

    /* Color Gamingevents/GameShow/Compatition */
    td.grsswk {
        background: #fee744;
    }

    /* Color GuestSigs/Workshops */
    td.gswssa {
        background: #fd92c0;
    }

    /* Color Artshow */
    td.as {
        background: #30c1b6;
    }

    /* Color Con-Ops */
    td.lsco {
        background: #ff441c;
    }

    /* Color Registration */
    td.reg {
        background: #1c2694;
    }

    td a {
        text-decoration: underline;
        box-sizing: content-box;
        display: block;
        color: #fff;
    }

    td a:hover {
        text-decoration: none;
        color: #cccccc;
    }

    tr.active {
        background: #e09592;
    }

    div.time {
        background: #d8f7f6;
    }

    tr.weekday {
        background: #cccccc;
        font-size: 22px;
    }

    strong.weekday {
        background: #cccccc;
        font-size: 22px;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    div.test {
        display: flex;
        color: #0b7437;
        font-size: 15px;
        font-weight: 800;
    }

    div.location {
        width: calc(100% - 2px);
    }

    div.time {
        width: calc(50% - 1px);
    }

    div.scrollmenu {
        background-color: #eee;
        overflow-x: auto;
        overflow-y: hidden;
    }

    div.scrollmenu li {
        display: inline-flex;
        color: white;
        text-align: center;
        text-decoration: none;
    }

    div.scrollmenu a:hover {
        background-color: #777;
    }
</style>
<!-- -->
<!-- Looking for an API? Ask @Kidran! -->
<!-- -->
@section('content')
    <div class="mt-4">

        <!-- Day Nav Tabs -->
        <div class="scrollmenu">
            <ul class="nav nav-tabs p-2 pb-1">
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
        <div class="tab-content">
            @foreach ($days as $index => $day)
                <div class="tab-pane{{ $loop->first ? ' active' : '' }}" id="ConDay{{ $index + 1 }}">
                    <div class="scrollmenu">
                        <ul class="nav nav-tabs p-2 pt-1">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#All{{ $index + 1 }}">
                                    All
                                </a>
                            </li>
                            @foreach ($locations as $location)
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab"
                                        href="#{{ Str::remove(['-', ' ', '+', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'], $location->name) . $index + 1 }}">
                                        {{ $location->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @php
                        $currentTime = \Illuminate\Support\Carbon::parse()
                    @endphp

                    <div class="tab-content">
                        <div class="tab-pane active" id="All{{ $index + 1 }}">
                            @foreach ($entries as $event)
                                @if ($event->start->format('d.m.Y') == $day)
                                    <div class="d-flex justify-content-center pt-2">
                                        <div class="card {{ $currentTime > $event->start && $currentTime < $event->end ? 'time' : '' }}" style="width: 50rem;">
                                            <div class="row g-0">
                                                <div class="col-md-3 text-center pt-2 border-light bg-light">
                                                    <h5>
                                                        {{ $event->start->format('H:i') }}
                                                    </h5>
                                                    <p>{{ $event->duration }} Min </p>
                                                </div>
                                                <div class="col-md-8 border-light">
                                                    <a href="{{ route('public.timeslot-show', $event->id) }}"class="nav nav-link">
                                                        <div class="card-body">
                                                            <div class="row text-start" style="margin-top: 0.5rem;">
                                                                <div class="col-12 col-md-12">
                                                                    <div class="col-12 text-start col-md-12">
                                                                        <h5><b><i class="bi bi-ticket-fill"></i>
                                                                            {{ $event->sigEvent->name }}</b>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="col-12 text-start col-md-12">
                                                                        @if ($event->sigEvent->sigHost->hide == 0)
                                                                        <i class="bi bi-person-fill"></i>
                                                                        {{ $event->sigEvent->sigHost->name }}
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-12 text-start col-md-12">
                                                                        <i class="bi bi-geo-fill"></i>
                                                                        {{ $event->sigEvent->sigLocation->name }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-md-1 border-light">
                                                    <div class="mt-1" style="display: flex; align-items: center; justify-content: center;">
                                                        @if (!is_null(auth()->user()))
                                                        @if ($event->getFavStatus())
                                                            <a type="button" class="btn text-danger btn-lg fav-btn" data-event="{{ $event->id }}">
                                                                <span id="fav-{{ $event->id }}" class="bi bi-heart-fill"></span>
                                                            </a>
                                                        @else
                                                            <a type="button" class="btn text-black btn-lg fav-btn" data-event="{{ $event->id }}">
                                                                <span id="fav-{{ $event->id }}" class="bi bi-heart"></span>
                                                            </a>
                                                        @endif
                                                    @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @foreach ($locations as $location)
                            <div class="tab-pane"
                                id="{{ Str::remove(['-', ' ', '+', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'], $location->name) . $index + 1 }}">
                                @foreach ($entries as $event)
                                    @if ($event->start->format('d.m.Y') == $day)
                                        @if ($event->sigEvent->sigLocation->id == $location->id)
                                            <div class="d-flex justify-content-center pt-2">
                                                <div class="card text-center {{ $currentTime > $event->start && $currentTime < $event->end ? 'time' : '' }}" style="width: 50rem;">
                                                    <div class="row g-0">
                                                        <div class="col-md-3 pt-2 border-light bg-light">
                                                            <h5>
                                                                {{ $event->start->format('H:i') }}
                                                            </h5>
                                                        </div>
                                                        <div class="col-md-8 border-light">
                                                            <a href="{{ route('public.timeslot-show', $event->id) }}"class="nav nav-link">
                                                                <div class="card-body">
                                                                    <div class="row text-start" style="margin-top: 0.5rem;">
                                                                        <div class="col-12 col-md-12">
                                                                            <div class="col-12 text-start col-md-12">
                                                                                <h5><b><i class="bi bi-ticket-fill"></i>{{ $event->sigEvent->name }}</b>
                                                                                </h5>
                                                                            </div>
                                                                            <div class="col-12 text-start col-md-12">
                                                                                @if ($event->sigEvent->sigHost->hide == 0)
                                                                                    <i class="bi bi-person-fill"></i>
                                                                                    {{ $event->sigEvent->sigHost->name }}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <div class="col-md-1 border-light">
                                                            <div class="mt-1" style="display: flex; align-items: center; justify-content: center;">
                                                                @if (!is_null(auth()->user()))
                                                                @if ($event->getFavStatus())
                                                                    <a type="button" class="btn text-danger btn-lg fav-btn" data-event="{{ $event->id }}">
                                                                        <span id="fav-{{ $event->id }}" class="bi bi-heart-fill"></span>
                                                                    </a>
                                                                @else
                                                                    <a type="button" class="btn text-black btn-lg fav-btn" data-event="{{ $event->id }}">
                                                                        <span id="fav-{{ $event->id }}" class="bi bi-heart"></span>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>

<script>
    $('.fav-btn').click(function () {
        let eventID = $(this).data('event');

        $.ajax({
            url: '/set-favorite',
            method: 'POST',
            data: {
                _token:'<?php echo csrf_token(); ?>',
                timetable_entry_id:eventID
            },
            dataType: 'JSON',
            success: function(data) {
               //Modal
               $('#fav-' . eventID).removeClass('bi-heart').addClass('bi-heart-fill');
            }
        });
    });

    function refreshPage() {
        // Versuche, eine Anfrage an einen bekannten Server durchzuführen
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/favicon.ico', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                location.reload();
            }
        };

        xhr.onerror = function () {
            // Es konnte keine Verbindung zum Server hergestellt werden
            console.log('Keine Verbindung zum Server');
        };

        xhr.send();
    }
        setInterval(refreshPage, 30000);
</script>
@endsection