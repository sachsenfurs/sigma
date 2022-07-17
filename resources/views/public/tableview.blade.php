@include('layouts.head')
@section('title', "Program")
<body>
<style>
    body,table {
        margin: 0;
        padding: 0;
        position: absolute;
        top: 0;
        left: 0;
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
        background: #dad55e;
    }
</style>
<table class="table">
    @foreach($days AS $day)
        <tr></tr>
        <tr>
            <td colspan="{{ count($locations)+1 }}">
                {{ $day }}
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
            <tr>
                @php
                    $currentTime = \Illuminate\Support\Carbon::parse($day)->setHours(8)->setMinutes(0)->addMinutes(30 * $y);
                @endphp

                <td>{{ $currentTime->format("H:i") }}</td>

                @php
                    for($x=0; $x<count($locations); $x++) {
                        if(isset($rowspan[$x][$y]))
                            continue;

                        $events = $entries->filter(function($value, $key) use ($locations, $currentTime, $x, $y) {
                            return ($value->start <= $currentTime)
                            && ($value->end >= $currentTime)
                            && (($value->sigLocation->name ?? "") == $locations[$x]->name);
                        });
                        if($events->first() && $events->first()->sigEvent) {
                            $sig = $events->first()->sigEvent;
                            $rows = ($events->first()->duration / 30)+1;
                            if($rows + $y > 32) {
                                $rows = 34-$y;
                            }
                            echo '<td rowspan="'.$rows.'" class="event">';
                            echo $sig->name;
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
