<div>
  <a href="{{route('home')}}">ホーム</a>
</div>

<div>
  <form method="POST" action="{{ route('logout') }}" >
    @csrf
    <x-responsive-nav-link :href="route('logout')"
      onclick="event.preventDefault();
      this.closest('form').submit();"
      style="padding: 0 !important;">
      {{ __('Log Out') }}
    </x-responsive-nav-link>
  </form>
</div>

<hr class="border-black opacity-50 border-2">
