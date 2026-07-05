<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SigFilledFormResource;
use App\Models\SigForm;
use Illuminate\Http\Resources\Json\JsonResource;

class SigFilledFormController extends Controller
{

    public function index(SigForm $sigForm) {
        abort_if(!request()->hasValidSignature(), 403);

        JsonResource::withoutWrapping();
        return SigFilledFormResource::collection($sigForm->sigFilledForms);
    }

}
