@section('title', 'Program')
@include('layouts.head')

<body>
    <style>
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
            background: #dbe8cf;
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
            color: #1c2688;
            font-size: 15px;
            font-weight: 800;
        }

        div.location {
            width: calc(100% - 2px);
        }

        div.time {
            width: calc(50% - 1px);
        }
    </style>
    <!-- -->
    <!-- Looking for an API? Ask @Kidran! -->
    <!-- -->

    <div class="container mt-4">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#home">{{ $days[0] }}</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#profile">{{ $days[1] }}</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#messages">{{ $days[2] }}</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <strong class="weekday">
                    {{ Str::upper(\Illuminate\Support\Carbon::parse($days[0])->dayName) . ' | ' . Str::upper(\Illuminate\Support\Carbon::parse($days[0])->locale('en')->dayName) }}
                </strong>

                <table class="table">
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
                </table>
            </div>
            <div class="tab-pane" id="profile">
                <strong>
                    {{ Str::upper(\Illuminate\Support\Carbon::parse($days[1])->dayName) . ' | ' . Str::upper(\Illuminate\Support\Carbon::parse($days[1])->locale('en')->dayName) }}
                </strong>
            </div>
            <div class="tab-pane" id="messages">
                <strong>
                    {{ Str::upper(\Illuminate\Support\Carbon::parse($days[2])->dayName) . ' | ' . Str::upper(\Illuminate\Support\Carbon::parse($days[2])->locale('en')->dayName) }}
                </strong>
            </div>
        </div>
    </div>
    <table class="table">
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
        @endforeach
    </table>
</body>
