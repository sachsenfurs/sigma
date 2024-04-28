@props([
    'title' => "",
    'id' => "",
    'labelId' => "label".rand(),
    'isForm' => isset($action) OR isset($method),
    'size' => "",
])
<div id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $labelId }}" aria-hidden="true" {{ $attributes->except(["action", "method", "title"])->merge(['class' => "modal fade"]) }}>
    <div class="modal-dialog">
        @section($labelId)
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $labelId }}">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __("Close") }}"></button>
                </div>
                <div class="modal-body">
                    {{ $slot }}
                </div>

                @if($attributes->get("footer") === null AND empty($footer))
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                        <button type="submit" class="btn btn-primary">{{ __("Save") }}</button>
                    </div>
                @else
                    @if($attributes->get("footer") !== false)
                        <div class="modal-footer">
                    @endif
                            {{ $footer ?? $attributes->get("footer") ?? "" }}
                    @if($attributes->get("footer"))
                        </div>
                    @endif
                @endempty
            </div>
        @endsection

        @if($isForm) <form id="{{$id}}Form" {{ $attributes->only(['action', 'method', 'data-editurl']) }}
            x-data="form" @submit.prevent="submit"> @endif
            @yield($labelId)
        @if($isForm) </form> @endif

    </div>
    <script>
        if({{$id}}.querySelectorAll("form").length > 0) {
            {{ $id }}.addEventListener('shown.bs.modal', () => {
                Array.from({{$id}}.querySelectorAll("form")[0].elements).filter((e) => e.type!=="hidden" && e.type!=="button")[0].focus();
            });

            let editId = null;
            {{ $id }}.addEventListener('show.bs.modal', (event) => {
                const triggerButton = event.relatedTarget;
                editId = triggerButton.getAttribute("data-id");
                let editUrl = {{$id}}Form.dataset.editurl + editId;
                {{--Alpine.evaluate({{$id}}Form, '$data').editId = editId;--}}
                if(editId) {
                    let elements = {{$id}}Form.elements;
                    window.el = elements;
                    axios.get(editUrl)
                        .then((response) => {
                            // response.data.item
                           // console.log(response.data.item)
                        });
                    // console.log(elements);
                }

            });

            {{ $id }}.addEventListener('hide.bs.modal', (event) => {
                // reset form
                if(editId !== null)
                    Array.from({{$id}}.querySelectorAll("form")[0].elements).map((e) => e.value = "");
            });
        }
    </script>
</div>
