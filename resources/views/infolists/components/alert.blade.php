<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div style="color: #fff; background: #d51717" class="rounded p-4 text-center">
        <span style="font-size: 1.8em">
            {{ $getState() }}
        </span>
        <p style="font-size: 1.2em">
            {{ $entry->subText }}
        </p>
    </div>
</x-dynamic-component>
