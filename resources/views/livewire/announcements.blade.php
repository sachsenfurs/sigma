<div>
    @foreach($posts AS $post)


        <div class="card mb-3">
            <div class="row g-0">
                @if($post->image)
                    <div class="col-12 col-md-4 text-center text-md-start" style="">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk("public")->url($post->image) }}" class="img-fluid rounded-start object-fit-cover d-none d-md-block" loading="lazy" style="max-height: 20em; width: 100%;">
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk("public")->url($post->image) }}" class="img-fluid rounded-top object-fit-cover d-md-none" loading="lazy" style="max-height: 15em; width: 100%;">
                    </div>
                @endif
                <div class="col">
                    <div class="card-body h-100 d-flex flex-column justify-content-between">
                        <p class="card-text fs-4">{{ $post->text }}</p>
                        <p class="card-text text-end">
                            <small class="text-body-secondary">{{ $post->created_at->diffForHumans() }}</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @if($limit != -1)
        <button class="btn btn-secondary" wire:click="showAll">
            {{ __("Show All") }}
        </button>
    @endif
</div>
