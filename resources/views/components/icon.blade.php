@props(['name'])

<svg {{ $attributes->merge([
    'class' => 'size-4',
    'xmlns' => 'http://www.w3.org/2000/svg',
    'viewBox' => '0 0 24 24',
    'fill' => 'none',
    'stroke' => 'currentColor',
    'stroke-width' => '2',
    'stroke-linecap' => 'round',
    'stroke-linejoin' => 'round',
    'aria-hidden' => 'true',
]) }}>
    @switch($name)
        @case('plus')
            <path d="M12 5v14M5 12h14" />
            @break
        @case('minus')
            <path d="M5 12h14" />
            @break
        @case('x')
            <path d="M18 6 6 18M6 6l12 12" />
            @break
        @case('search')
            <circle cx="11" cy="11" r="7" />
            <path d="m20 20-3.5-3.5" />
            @break
        @case('chevron-right')
            <path d="m9 6 6 6-6 6" />
            @break
        @case('chevron-left')
            <path d="m15 6-6 6 6 6" />
            @break
        @case('check')
            <path d="m5 12 5 5L20 7" />
            @break
        @case('pencil')
            <path d="M12 20h9" />
            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
            @break
        @case('trash')
            <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6" />
            @break
    @endswitch
</svg>
