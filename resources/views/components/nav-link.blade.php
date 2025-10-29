@props(['active'])

@php
$classes = ($active ?? false)
      ? 'd-inline-flex align-items-center px-2 pt-2 link-primary fw-semibold'
      : 'd-inline-flex align-items-center px-2 pt-2 link-primary fw-semibold';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
  {{ $slot }}
</a>
