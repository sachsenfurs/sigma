@section('title', "Program")
@include('layouts.head')
<body>
<style>
    table {
        margin: 0;
        padding: 0;
        width: calc(100% - 2px);
    }
    table, tr,td {
        box-sizing: border-box;
    }
    td.event {
        border: 2px solid #000;
        text-align: center;
        vertical-align: middle;
        font-size: 1.4em;
        color: #fff;
        background: #b78d2b;
        cursor: pointer;
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
</style>
<!-- -->
<!-- Looking for an API? Ask @Kidran! -->
<!-- -->
<table class="table">
    @foreach($days AS $day)
        <tr></tr>
        <tr class="weekday">
            <td colspan="{{ count($locations)+1 }}">
                <strong>{{ $day }} - {{ Str::upper(\Illuminate\Support\Carbon::parse($day)->dayName) . " | " . Str::upper(\Illuminate\Support\Carbon::parse($day)->locale("en")->dayName) }}</strong>
            </td>
        </tr>
        <tr>
            <th></th>
            @foreach($locations AS $location)
                <th>
                    {{ $location->name }}
                </th>
            @endforeach
        </tr>
        @php
            $rowspan = []
        @endphp
        @for($y=0; $y<=32; $y++)
            @php
                $currentTime = \Illuminate\Support\Carbon::parse($day)->setHours(8)->setMinutes(0)->addMinutes(30 * $y);
            @endphp
            <tr class="{{ $currentTime > \Illuminate\Support\Carbon::now() && $currentTime < \Illuminate\Support\Carbon::now()->addMinutes(30) ? "active" : "" }}">
                <td>{{ $currentTime->format("H:i") }}</td>

                @php
                    for($x=0; $x<count($locations); $x++) {
                        if(isset($rowspan[$x][$y]))
                            continue;

                        $events = $entries->filter(function($value, $key) use ($locations, $currentTime, $x, $y) {
                            return ($value->start <= $currentTime)
                            && ($value->end > $currentTime)
                            && (($value->sigLocation->name ?? "") == $locations[$x]->name);
                        });
                        if($events->first() && $events->first()->sigEvent) {
                            $sig = $events->first()->sigEvent;
                            $rows = ($events->first()->duration / 30);

                            if($rows + $y > 32) {
                                $rows = 34-$y;
                            }
                            echo '<td rowspan="'.$rows.'" class="event">';
                            echo '<a href="'. route("public.timeslot-show", $events->first()) .'">';
                            echo $sig->name;
                            echo '</a>';
                            echo '</td>';
                            for($i=0; $i<$rows; $i++) {
                                $rowspan[$x][$y+$i] = true;
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
