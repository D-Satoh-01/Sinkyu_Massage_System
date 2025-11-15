<!-- resources/views/master-registration/master-index.blade.php -->


<x-app-layout>
  <h2>マスター登録</h2><br><br>

  ・<a href="{{ route('clinic-users.index') }}">利用者情報</a><br>
  ・<a href="{{ route('doctors.index') }}">医師情報</a><br>
  ・<a href="{{ route('therapists.index') }}">施術者情報</a><br>
  ・<a href="{{ route('caremanagers.index') }}">ケアマネ情報</a><br>
  ・<a href="{{ route('company-info.index') }}">自社情報</a><br>
  ・<a href="{{ route('submaster.index') }}">サブマスター編集</a><br>
  ・<a href="{{ route('master.documents.index') }}">文面編集</a><br>
  ・<a href="{{ route('master.treatment-fees.index') }}">施術料金編集</a><br>
  ・<a href="{{ route('master.self-fees.index') }}">自費施術料金編集</a><br>
  ・<a href="{{ route('master.document-association.index') }}">現在の登録済み標準文書の確認および関連付け</a>
</x-app-layout>
