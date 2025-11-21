// public/js/sidebar.js

document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const toggleButton = document.getElementById('sidebar-toggle');
  const mainContent = document.querySelector('.main-content');
  const header = document.querySelector('.app-header');

  // ヘッダーの高さを動的に取得して適用
  function updateLayoutHeights() {
    if (header && sidebar && mainContent) {
      const headerHeight = header.offsetHeight;
      sidebar.style.top = `${headerHeight}px`;
      sidebar.style.height = `calc(100vh - ${headerHeight}px)`;
      mainContent.style.marginTop = `${headerHeight}px`;
    }
  }

  // 初期化時とウィンドウリサイズ時に高さを更新
  updateLayoutHeights();
  window.addEventListener('resize', updateLayoutHeights);

  // ローカルストレージからサイドバーの状態を読み込み
  const sidebarState = localStorage.getItem('sidebarState');

  if (sidebarState === 'closed') {
    sidebar.classList.add('closed');
    if (mainContent) {
      mainContent.classList.add('sidebar-closed');
    }
  }

  // 事前読み込み用クラスを削除
  document.documentElement.classList.remove('sidebar-preload-closed');

  // トグルボタンのクリックイベント
  if (toggleButton) {
    toggleButton.addEventListener('click', function() {
      sidebar.classList.toggle('closed');

      if (mainContent) {
        mainContent.classList.toggle('sidebar-closed');
      }

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
