@include('layouts.head')
@section('title', "Program")
<body>

<table class="table" style="width:100%">
    <tr>
        <th></th>
        @foreach($locations AS $location)
            <th>
                {{ $location->name }}
            </th>
        @endforeach
    </tr>
    @for($i=0; $i<=32; $i++)
        <tr>
            <td>{{ \Illuminate\Support\Carbon::now()->setHours(8)->setMinutes(0)->addMinutes(30 * $i)->format("H:i") }}</td>
            @for($x=0; $x<count($locations); $x++)
                <td></td>
            @endfor
        </tr>
    @endfor
</table>

</body>
