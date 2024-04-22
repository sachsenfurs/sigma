@extends('layouts.app')
@section('title', $form->name_localized)
@section('content')
    <div class="container">
        @if($form->form_closed)
            <div class="row justify-content-center mb-4">
                <div class="col-md-6">
                    <div class="alert alert-danger">
                        {{ __('This form has been marked as "closed". This means that no new submissions and no further edits are possible.') }}
                    </div>
                </div>
            </div>
        @endif
        @switch($filledForm->approved ?? null)
            @case(0)
                <div class="row justify-content-center mb-4">
                    <div class="col-md-6">
                        <div class="alert alert-warning">
                            {{ __('This form has not been approved yet.') }}
                        </div>
                    </div>
                </div>
            @break
            @case(1)
                <div class="row justify-content-center mb-4">
                    <div class="col-md-6">
                        <div class="alert alert-success">
                            {{ __('This form has been approved.') }}
                        </div>
                    </div>
                </div>
            @break
            @case(2)
                <div class="row justify-content-center mb-4">
                    <div class="col-md-6">
                        <div class="alert alert-danger">
                            {{ __('This form has been rejected. Reason for rejection: ') . ($filledForm->rejection_reason ?? __('No reason available')) }}
                        </div>
                    </div>
                </div>
            @break
        @endswitch
        @if($form->sig_event_id)
            <div class="row justify-content-center mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <strong>SIG</strong>
                        </div>
                        <div class="card-body">
                            @can('manage_events')
                                <a href="{{ route('sigs.show', ['sig' => $form->sig_event_id]) }}">
                                    {{ $form->sigEvent->name_localized }}
                                </a>
                            @else
                                {{ $form->sigEvent->name_localized }}
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-6">
                @if(!$form->form_closed)
                    <form method="POST" enctype="multipart/form-data">
                @endif
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <strong>{{ $form->name_localized }}</strong>
                        </div>
                        <div class="card-body">
                            @foreach($form->form_definition as $formField)
                                @php($formData = $formField['data'])
                                @php($filledData = $filledForm['form_data']['form_data'][$formData['name']] ?? null)
                                <div class="form-group">
                                    <label
                                        for="{{ $formData['name'] }}"
                                        class="form-label mt-2"
                                    >
                                        {{ App::getLocale() == 'en' ? ($formData['label_en'] ?? $formData['label']) : $formData['label'] }}@if($formData['required'] ?? false)*@endif:
                                    </label>
                                    @switch($formField['type'])
                                        @case('text')
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="{{ $formData['name'] }}"
                                                name="form_data[{{ $formData['name'] }}]"
                                                value="{{ old($formData['name'], $filledData) }}"
                                                @if($formData['required'] ?? false) required @endif
                                                @if($form->form_closed) disabled readonly @endif
                                            />
                                            @break
                                        @case('textarea')
                                            <textarea
                                                class="form-control"
                                                id="{{ $formData['name'] }}"
                                                name="form_data[{{ $formData['name'] }}]"
                                                @if($formData['required'] ?? false) required @endif
                                                @if($form->form_closed) disabled readonly @endif
                                            >{{ old($formData['name'], $filledData) }}</textarea>
                                            @break
                                        @case('file_upload')
                                            @if ($filledData)
                                                <img class="form-control" src="{{ Storage::url($filledData) }}" alt="" />
                                                <input type="hidden" value="{{ $filledData }}" name="form_data[{{ $formData['name'] }}]">
                                            @endif
                                            <input
                                                type="file"
                                                class="form-control"
                                                id="{{ $formData['name'] }}"
                                                name="form_data[{{ $formData['name'] }}]"
                                                @if($formData['required'] ?? false) required @endif
                                                @if($form->form_closed) disabled readonly @endif
                                            />
                                            @break
                                        @case('checkbox')
                                            <input type="hidden" value="0" name="{{ $formData['name'] }}">
                                            <input
                                                type="checkbox"
                                                id="{{ $formData['name'] }}"
                                                name="form_data[{{ $formData['name'] }}]"
                                                value = "1"
                                                @if(old($formData['name'], $filledData) == 1) checked @endif
                                                @if($formData['required'] ?? false) required @endif
                                                @if($form->form_closed) disabled readonly @endif
                                            />
                                            @break
                                        @case('select')
                                            <select
                                                id="{{ $formData['name'] }}"
                                                name="form_data[{{ $formData['name'] }}]"
                                                class="form-control"
                                                @if($formData['required'] ?? false) required @endif
                                                @if($form->form_closed) disabled readonly @endif
                                            >
                                                <option
                                                    value=""
                                                    disabled
                                                    selected
                                                >
                                                    {{ __('Please select...') }}
                                                </option>
                                                @foreach($formData['options'] as $option)
                                                    @php($optionData = $option['data'])
                                                    <option
                                                        value="{{ $optionData['value'] }}"
                                                        @if(old($formData['name'], $filledData) == $optionData['value']) selected @endif
                                                    >
                                                        {{ App::getLocale() == 'en' ? ($optionData['label_en'] ?? $optionData['label']) : $optionData['label'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @break
                                    @endswitch
                                </div>
                            @endforeach
                            <div class="mt-4">
                                @if(!$form->form_closed)
                                    <button class="btn btn-success">{{ __('Save') }}</button>
                                    @if($filledForm)
                                        <a type="button" class="btn btn-danger" onclick="$('#deleteModal').modal('show');" data-toggle="modal" data-target="#deleteModal">{{ __('Delete') }}</a>
                                    @endif
                                @endif
                            </div>
                            @if($filledForm)
                                <div>
                                    <small class="text-muted ml-2">{{ __('When the form is saved, the data must be checked and approved again.') }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                @if(!$form->form_closed)
                    </form>
                @endif
            </div>
        </div>
    </div>
    @if($filledForm)
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">{{ $form->name_localized }}</h5>
                    </div>
                    <div class="modal-body">
                        {{ __('Really delete form data?') }}
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm" method="POST">
                            @method('DELETE')
                            @csrf
                            <a class="btn btn-secondary" onclick="$('#deleteModal').modal('hide');" data-dismiss="modal">{{ __("Cancel") }}</a>
                            <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
