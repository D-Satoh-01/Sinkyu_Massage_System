<!-- resources/views/master-registration/mr-index.blade.php -->


<x-app-layout>
  <h2>マスター登録</h2><br><br>

  ・<a href="{{ route('clinic-users.index') }}">利用者情報</a><br>
  ・<a href="{{ route('doctors.index') }}">医師情報</a><br>
  ・<a href="{{ route('therapists.index') }}">施術者情報</a><br>
  ・<a href="{{ route('caremanagers.index') }}">ケアマネ情報</a>
</x-app-layout>
