@extends('layouts.app')
@section('title', __("Dealers Den Sign Up"))

@section('content')

    <div class="container">
        <h2>{{ __('Dealers Den Sign Up') }}</h2>
        <form action="{{ route("dealersden.store") }}" method="POST">
            @csrf
            <div class="card mt-3">
                <div class="card-header">
                    {{ __("General Information") }}
                </div>
                <div class="card-body">
                    <div class="row g-3 align-items-start">
                        <div class="col-md-5 col-7 col-lg-3">
                            <label class="container rounded bg-body border p-3" style="cursor: pointer" for="selectImage">
                                Bild (optional)
                                <div class="text-center" style="aspect-ratio: 1">
                                    <img id="preview" src="#" alt="" class="rounded" style="display: none; width: 100%; height: 100%;  object-fit: cover"/>
                                </div>
                            </label>
                            <input style="display: none" type="file" accept="image/*" class="form-control" name="image" id="selectImage">
                            <script>
                                selectImage.onchange = evt => {
                                    let preview = document.getElementById('preview');
                                    preview.style.display = 'block';
                                    const [file] = selectImage.files
                                    if (file) {
                                        preview.src = URL.createObjectURL(file)
                                    }
                                }
                            </script>
                        </div>
                        <div class="col-md-7 col-12 col-lg-9">
                            <div class="row g-3 d-flex">
                                <div class="col-12">
                                    <x-form.input-floating  :placeholder="__('Dealers Name')" name="name" />
                                </div>
                                <div class="col-12">
                                    <x-form.input-floating  :placeholder="__('Gallery Link, Website, ...')" name="name" />
                                </div>
                                <div class="col-12" style="height: 100%">
                                    <label for="info">Info</label>
                                    <textarea id="info" style="width: 100%" rows="6" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="container text-center p-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">{{ __("Submit") }}</button>
            </div>
        </form>
    </div>
@endsection
