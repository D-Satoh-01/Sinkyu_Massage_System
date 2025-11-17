<!-- resources/views/clinic-info/clinic-info_index.blade.php -->

<x-app-layout>
  <h2>自社情報</h2><br><br>

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

  <form action="{{ route('clinic-info.confirm') }}" method="POST">
    @include('clinic-info.components.clinic-info_form', [
      'companyInfo' => $companyInfo,
      'prefectures' => $prefectures,
      'bankAccountTypes' => $bankAccountTypes,
      'healthCenterLocations' => $healthCenterLocations,
      'documentFormats' => $documentFormats,
      'submitLabel' => '登録確認へ',
      'cancelRoute' => route('master.index')
    ])
  </form>
</x-app-layout>
