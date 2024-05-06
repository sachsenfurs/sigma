@props([
    'name' => "",
    'id' => "selectImageFile".rand(),
    'style' => "",
    'previewId' => "preview",
])
<input type="file" accept="image/*" {{ $attributes->merge(['class' => "form-control"]) }} name="{{ $name }}" id="{{ $id }}">
<script>
    {{ $id }}.onchange = evt => {
        let preview = document.getElementById('{{ $previewId }}');
        preview.style.display = 'block';
        const [file] = {{ $id }}.files
        if (file) {
            preview.src = URL.createObjectURL(file)
        }
    }
</script>
