<?php

namespace App\Observers;


use App\Models\SigFilledForm;
use App\Notifications\Forms\SigFilledFormProcessedNotification;
use Illuminate\Support\Facades\Storage;

class SigFilledFormObserver
{
    public function deleted(SigFilledForm $filledForm): void {
        $sigForm = $filledForm->sigForm;
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

    public function updated(SigFilledForm $filledForm): void {
        if($filledForm->isDirty("approval")) {
            $filledForm->user->notify(
                new SigFilledFormProcessedNotification($filledForm)
            );
        }
    }

}
