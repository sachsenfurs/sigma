<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\SigForm;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SigFormController extends Controller
{
    public function show(SigForm $form) {
        $filledForm = $form->sigFilledForms()->whereUserId(auth()->user()->id)->first();

        return view('forms.createEdit', compact([
            'form',
            'filledForm'
        ]));
    }

    public function store(SigForm $form, Request $request) {
        if ($form->form_closed) {
            return redirect()->back()->withErrors(__('Form is closed'));
        }

        // Build validation rules
        $validationRules = [];
        $validationNames = [];
        foreach ($form->form_definition as $formDefinition) {
            $formData = $formDefinition['data'];
            $formValidation = [
                'min:' . ($formData['min'] ?? 0),
                'max:' . ($formData['max'] ?? ($formDefinition['type'] == "number" ? PHP_INT_MAX : 1000)),
            ];
            if ($formData['required'] ?? false) {
                $formValidation[] = 'required';
            }
            if(($formDefinition['type'] ?? "text") == "number") {
                $formValidation[] = 'numeric';
            }
            $validationRules['form_data.' . $formData['name']] = implode('|', $formValidation);
            $validationNames['form_data.' . $formData['name']] = app()->getLocale() == "en" ? data_get($formData, "label_en") : data_get($formData, "label");
        }

        $validated = Validator::make($request->all(), $validationRules, [], $validationNames)->validate();

        foreach ($validated['form_data'] as &$item) {
            if ($item instanceof UploadedFile) {
                $item = Storage::disk('public')->putFile('forms/' . $form->id, $item);
            }
        }

        $filledForm = $form->sigFilledForms()->whereUserId(auth()->user()->id)->firstOrNew();
        $filledForm->sigForm()->associate($form);
        $filledForm->user()->associate(auth()->user());
        $filledForm->form_data = $validated;

        // Do not reset reject reason, so we know why it was rejected
        $filledForm->save();

        return redirect()->back()->withSuccess(__('Form data saved'));
    }

    public function destroy(SigForm $form) {
        $filledForm = $form->sigFilledForms()->whereUserId(auth()->user()->id)->first();

        if (!$filledForm) {
            return redirect(route('forms.show', [ $form->slug ]));
        }

        if ($filledForm->delete()) {
            return redirect()->back()->withSuccess(__('Form data deleted'));
        } else {
            return redirect()->back()->withErrors('Couldn\'t delete form data');
        }
    }

}
