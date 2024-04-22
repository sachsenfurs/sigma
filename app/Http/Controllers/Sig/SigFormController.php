<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\SigFilledForms;
use App\Models\SigForms;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SigFormController extends Controller
{
    public function show(SigForms $form) {
        $form = SigForms::with('sigEvent')
            ->where('slug', $form->slug)
            ->first();
        if (!$form) {
            abort(404);
        }

        $filledForm = SigFilledForms::with('sigForms')
            ->where('sig_forms_id', $form->id)
            ->where('user_id', auth()->user()->id)
            ->first();

        return view('forms.createEdit', compact([
            'form',
            'filledForm'
        ]));
    }

    public function store(SigForms $form, Request $request) {
        $form = SigForms::with('sigEvent')
            ->where('slug', $form->slug)
            ->first();
        if (!$form) {
            abort(404);
        }
        if ($form->form_closed) {
            return redirect(route('forms.show', [ $form->slug ]))->withErrors(__('Form is closed'));
        }

        // Build validation rules
        $validationRules = [];
        foreach ($form->form_definition as $formDefinition) {
            $formData = $formDefinition['data'];
            $formValidation = [];
            if ($formData['required'] ?? false) {
                $formValidation[] = 'required';
            }
            $validationRules['form_data.' . $formData['name']] = implode('|', $formValidation);
        }
        $validated = $request->validate($validationRules);

        foreach ($validated['form_data'] as &$item) {
            if ($item instanceof UploadedFile) {
                $item = Storage::disk('public')->putFile('forms/' . $form->id, $item);
            }
        }

        $filledForm = SigFilledForms::with('sigForms')
            ->where('sig_forms_id', $form->id)
            ->where('user_id', auth()->user()->id)
            ->first();
        if (!$filledForm) {
            $filledForm = new SigFilledForms();
        }
        $filledForm->sigForms()->associate($form);
        $filledForm->user()->associate(auth()->user());
        $filledForm->form_data = $validated;
        $filledForm->save();

        return redirect(route('forms.show', [ $form->slug ]))->withSuccess(__('Form data saved'));
    }

    public function destroy(SigForms $form) {
        $form = SigForms::with('sigEvent')
            ->where('slug', $form->slug)
            ->first();
        if (!$form) {
            abort(404);
        }

        $filledForm = SigFilledForms::with('sigForms')
            ->where('sig_forms_id', $form->id)
            ->where('user_id', auth()->user()->id)
            ->first();

        if (!$filledForm) {
            return redirect(route('forms.show', [ $form->slug ]));
        }

        if ($filledForm->delete()) {
            return redirect(route('forms.show', [ $form->slug ]))->withSuccess(__('Form data deleted'));
        } else {
            return redirect(route('forms.show', [ $form->slug ]))->withErrors('Couldn\'t delete form data');
        }
    }

}
