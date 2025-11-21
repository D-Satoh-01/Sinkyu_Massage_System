<!-- resources/views/index.blade.php -->


<x-app-layout>
  <h2>ホーム</h2><br><br>

  <ul>
    <li><a href="{{ route('records.index') }}">実績データ</a></li>
    <li><a>報告書データ</a></li>
    <li><a>スケジュール</a></li>
    <li><a href="{{ route('master.index') }}">マスター登録</a></li>
    <li><a>印刷メニュー</a></li>
    <li><a>要加療期限リスト</a></li>
    <li><a>入金管理</a></li>
  </ul>
</x-app-layout>
