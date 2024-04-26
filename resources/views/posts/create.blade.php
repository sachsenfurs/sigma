@extends('layouts.app')
@section('title', __("SF Post"))

@section('content')
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <form method="POST" enctype="multipart/form-data" action="{{ route("posts.store") }}">
                    @csrf
                    <div class="card-header">@lang('Create Post')</div>
                    <div class="card-body">
                        <script>
                            $(document).ready(function() {
                                $('#translate').click(function (){
                                    let text = $('#text_de').val();
                                    axios.post("{{ route("posts.translate") }}", {text: text})
                                        .then(function(response) {
                                            $('#text_en').val(response.data[0].text);
                                        });
                                });
                            });
                        </script>
                        <div class="mb-3">
                            <label class="form-label">Message DE</label>
                            <textarea name="text_de" id="text_de" rows="3" class="form-control" style="font-size: 22px">{{ old("text_de") }}</textarea>
                        </div>
                        <div class="mb-3 text-center">
                            <button type="button" class="btn btn-secondary" id="translate">Translate</button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message EN</label>
                            <textarea name="text_en" id="text_en" rows="3" class="form-control" style="font-size: 22px">{{ old("text_en") }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bild (optional)</label>
                            <input type="file" accept="image/*" class="form-control" name="image" id="selectImage">
                            <img id="preview" src="#" alt="your image" class="p-3 object-fit-cover" style="display:none; width: 100%;height: 100%"/>
                        </div>
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

                        <table class="table">
                            <tr>
                                <th>Posting to...</th>
                                <th>DE</th>
                                <th>EN</th>
                            </tr>
                            <tr>
                                <td>EAST Info Deutsch</td>
                                <td><input type="checkbox" name="tg1" class="form-check-input" checked disabled></td>
                                <td><input type="checkbox" name="tg1" class="form-check-input" disabled></td>
                            </tr>
                            <tr>
                                <td>EAST Info Englisch</td>
                                <td><input type="checkbox" name="tg2" class="form-check-input" disabled></td>
                                <td><input type="checkbox" name="tg2" class="form-check-input" checked disabled></td>
                            </tr>
                        </table>
                        <input type="submit" class="btn btn-primary" value="POST IT">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
