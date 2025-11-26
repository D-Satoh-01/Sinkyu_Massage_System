<!-- resources/views/layouts/app.blade.php -->


<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>{{ config('app.name', 'Laravel') }}</title>

		<!-- Google Fonts (Noto Sans JP) -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">		

		<!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

		<!-- DataTables CSS -->
		<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

		<!-- スクリプト -->
		@vite(['resources/css/app.css', 'resources/js/app.js'])
	</head>
	<body class="app-layout min-vh-100">

		<!-- ヘッダー -->
		<header class="app-header">
			@include('layouts.header')
		</header>

		<!-- サイドバー状態の事前読み込み（ちらつき防止） -->
		<script>
			(function() {
				if (localStorage.getItem('sidebarState') === 'closed') {
					document.documentElement.classList.add('sidebar-preload-closed');
				}
			})();
		</script>

		<!-- Content Wrapper（サイドバー ＋ メインコンテンツ） -->
		<div class="content-wrapper">
			<!-- サイドバー -->
			@include('layouts.sidebar')

			<!-- メインコンテンツ -->
			<div class="main-content">
				<main>
					{{ $slot }}
				</main>
			</div>
		</div>

		<!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

		<!-- jQuery (required for DataTables) -->
		<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

		<!-- DataTables JS -->
		<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

		<!-- サイドバー JS -->
		<script src="{{ asset('js/sidebar.js') }}"></script>

		@stack('scripts')
	</body>
</html>
