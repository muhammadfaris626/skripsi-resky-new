@props(['icon' => null])

<button type="button" {{ $attributes->merge(['class' => 'px-3 py-1 rounded-md text-sm font-medium hover:bg-white/50 flex items-center']) }}>
    @if($icon)
        <span class="me-2">
            {{-- Use Flux icon component (published under resources/views/flux/icon/*.blade.php) --}}
            <flux:icon :icon="$icon" class="w-4 h-4 inline-block" />
        </span>
    @endif
    <span>{{ $slot }}</span>
</button>
