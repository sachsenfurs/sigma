<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TimetableEntry;
use App\Services\BarqScheduleBuilder;
use Kidran\EventScheduleSchema\Validation\EventScheduleValidator;

class BarqScheduleEndpoint extends Controller
{
    public function __invoke(BarqScheduleBuilder $builder, EventScheduleValidator $validator) {
        if(! request()->hasValidSignature()) {
            $this->authorize('viewAny', TimetableEntry::class);
        }

        $schedule = $builder->build();
        $result = $validator->validate($schedule, domain: true);

        if($result->fails()) {
            return response()->json([
                'message' => 'Generated BARQ schedule is invalid.',
                'errors' => $result->toArray(),
            ], 500);
        }

        return response()->json($schedule->toArray());
    }
}
