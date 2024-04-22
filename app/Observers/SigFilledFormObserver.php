<?php

namespace App\Observers;


use App\Models\SigFilledForms;
use Illuminate\Support\Facades\Storage;

class SigFilledFormObserver
{
    public function deleted(SigFilledForms $filledForm)
    {
        $sigForm = $filledForm->sigForms;
        foreach ($sigForm->form_definition as $formDefinition) {
            if ($formDefinition['type'] !== 'file_upload') {
                continue;
            }
            $fileKey = $formDefinition['data']['name'];
            $file = $filledForm->form_data['form_data'][$fileKey] ?? null;
            if ($file) {
                Storage::disk('public')->delete($file);
            }
        }
    }


}
