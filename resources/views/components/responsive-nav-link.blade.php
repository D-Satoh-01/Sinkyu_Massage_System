<!-- resources/views/components/responsive-nav-link.blade.php -->


@props(['active'])

@php
$classes = ($active ?? false)
      ? 'd-block w-100 px-3 py-2 border-primary text-start fw-medium link-primary bg-light'
      : 'd-block w-100 px-3 py-2 border-transparent text-start fw-medium link-primary';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
  {{ $slot }}
</a>
