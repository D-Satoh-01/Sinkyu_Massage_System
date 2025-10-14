<!-- resources/views/clinic-users-info/cui-insurances-info/cii-home.blade.php -->


<x-app-layout>
  <h2>{{ $name }} 様の保険情報</h2>
  <br><br>

  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- 保険情報新規登録ボタン -->
  <a href="{{ route('cui-insurances-info.registration', $id) }}">
    <button type="button">保険情報新規登録</button>
  </a>
  <br><br>
</x-app-layout>

