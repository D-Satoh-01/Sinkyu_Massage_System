<!-- resources/views/layouts/sidebar.blade.php -->


<aside id="sidebar" class="sidebar">
  <div class="sidebar-header">
    <div class="user-info">
      <div class="user-name">ログインユーザー名<br>：<b>{{ Auth::user()->name }}</b></div>
      <div class="last-login">
        @if(Auth::user()->last_login_at)
          前回ログイン日時<br>：{{ Auth::user()->last_login_at->format('Y/m/d H:i') }}
        @else
          前回ログイン：
        @endif
      </div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <ul class="sidebar-menu">
      <li class="sidebar-menu-item">
        <a href="{{ route('records.index') }}" class="sidebar-link">実績データ</a>
      </li>
      <li class="sidebar-menu-item">
        <a href="#" class="sidebar-link">報告書データ</a>
      </li>
      <li class="sidebar-menu-item">
        <a href="#" class="sidebar-link">スケジュール</a>
      </li>
      <li class="sidebar-menu-item">
        <a href="{{ route('master.index') }}" class="sidebar-link">マスター登録</a>
      </li>
      <li class="sidebar-menu-item">
        <a href="#" class="sidebar-link">印刷メニュー</a>
      </li>
      <li class="sidebar-menu-item">
        <a href="#" class="sidebar-link">要加療期限リスト</a>
      </li>
      <li class="sidebar-menu-item">
        <a href="#" class="sidebar-link">入金管理</a>
      </li>
    </ul>
  </nav>
</aside>
