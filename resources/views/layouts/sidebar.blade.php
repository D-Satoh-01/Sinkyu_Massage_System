<!-- resources/views/layouts/sidebar.blade.php -->


<aside id="sidebar" class="sidebar border-end border-secondary border-2 bg-body-secondary">
  <div class="border-bottom border-secondary border-2 px-3 py-2">
    <div class="d-flex flex-column gap-2">
      <div class="small text-dark-emphasis">ログインユーザー名<br>：<b>{{ Auth::user()->name }}</b></div>
      <div class="small text-dark-emphasis">
        @if(Auth::user()->last_login_at)
          前回ログイン日時<br>：{{ Auth::user()->last_login_at->format('Y/m/d H:i') }}
        @else
          前回ログイン：
        @endif
      </div>
    </div>
  </div>

  <nav class="p-0">
    <ul class="list-unstyled p-0 m-0">
      <li class="border-bottom border-secondary">
        <a href="{{ route('records.index') }}" class="sidebar-link">実績データ</a>
      </li>
      <li class="border-bottom border-secondary">
        <a href="{{ route('reports.index') }}" class="sidebar-link">報告書データ</a>
      </li>
      <li class="border-bottom border-secondary">
        <a href="{{ route('schedules.index') }}" class="sidebar-link">スケジュール</a>
      </li>
      <li class="border-bottom border-secondary">
        <a href="{{ route('master.index') }}" class="sidebar-link">マスター登録</a>
      </li>
      <li class="border-bottom border-secondary">
        <a href="#" class="sidebar-link">印刷メニュー</a>
      </li>
      <li class="border-bottom border-secondary">
        <a href="#" class="sidebar-link">要加療期限リスト</a>
      </li>
      <li class="border-bottom border-secondary">
        <a href="#" class="sidebar-link">入金管理</a>
      </li>
    </ul>
  </nav>
</aside>
