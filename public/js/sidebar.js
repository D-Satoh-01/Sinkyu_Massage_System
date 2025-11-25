// public/js/sidebar.js

document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const toggleButton = document.getElementById('sidebar-toggle');

  // ローカルストレージからサイドバーの状態を読み込み
  const sidebarState = localStorage.getItem('sidebarState');

  if (sidebarState === 'closed') {
    sidebar.classList.add('closed');
  }

  // 事前読み込み用クラスを削除
  document.documentElement.classList.remove('sidebar-preload-closed');

  // トグルボタンのクリックイベント
  if (toggleButton) {
    toggleButton.addEventListener('click', function() {
      sidebar.classList.toggle('closed');

      // 状態をローカルストレージに保存
      if (sidebar.classList.contains('closed')) {
        localStorage.setItem('sidebarState', 'closed');
      } else {
        localStorage.setItem('sidebarState', 'open');
      }
    });
  }

  // アクティブリンクの設定
  const currentPath = window.location.pathname;
  const sidebarLinks = document.querySelectorAll('.sidebar-link');

  sidebarLinks.forEach(link => {
    if (link.getAttribute('href') === currentPath) {
      link.classList.add('active');
    }
  });
});
