@props(['active', 'logoName'])

@php
$classes = ($active ?? false) ? 'nav-link active' : 'nav-link link-dark' ;            
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if (isset($logoName))
    <i class="bi bi-{{ $logoName }}" width="16" height="16"></i>
    @endif
    {{ $slot }}
</a>
