<!-- resources/views/components/secondary-button.blade.php -->

<button {{ $attributes->merge(['type' => 'button', 'class' => 'd-inline-flex align-items-center px-3 py-2 bg-white border border-secondary rounded fs-6 text-secondary text-uppercase fw-semibold shadow-sm hover:bg-light focus:outline-none focus:ring focus:ring-primary focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
  {{ $slot }}
</button>
