@props(['variant' => 'default'])

@php
    // Minimal tabs wrapper - variant can be used to tweak styles
    $base = 'flex flex-wrap items-center gap-2';
    if ($variant === 'segmented') {
        $base .= ' bg-gray-200 p-1 rounded-md inline-flex w-fit';
    }
@endphp

<div {{ $attributes->merge(['class' => $base]) }}>
    {{ $slot }}
</div>
