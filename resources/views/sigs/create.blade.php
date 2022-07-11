@extends('layouts.app')
@section('title', "SIG Anlegen")
@section('content')
    <div class="container  ">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <form method="POST" action="{{ route("sigs.store") }}">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            SIG Anlegen
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label for="name" class="form-label">SIG Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old("name") }}" autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="host" class="form-label">SIG Host</label>
                                <input type="text" class="form-control" id="host" name="host" value="{{ old("host") }}">
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            var availableTags = @json($hosts);
            $( "#host" ).autocomplete({
                source: availableTags,
                minLength: 0,
                delay: 0,
            });
        });
    </script>
@endsection
