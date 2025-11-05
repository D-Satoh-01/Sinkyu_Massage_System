<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン</title>
</head>
<body>
  <h2>ログイン</h2>

  <form method="POST" action="{{ route('login') }}" id="loginForm" onsubmit="console.log('Form submitted'); return true;">
    @csrf

    @if ($errors->any())
      <div style="color: red;">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div>
      <label for="login_id">ユーザーID</label>
      <input id="login_id" type="text" name="login_id" value="{{ old('login_id') }}" required autofocus>
      @error('login_id')
        <div style="color: red;">{{ $message }}</div>
      @enderror
    </div>

    <div>
      <label for="password">パスワード</label>
      <input id="password" type="password" name="password" required>
      @error('password')
        <div style="color: red;">{{ $message }}</div>
      @enderror
    </div>

    <div>
      <label>
        <input type="checkbox" name="remember"> ログイン状態を保持
      </label>
    </div>

    <button type="submit" onclick="console.log('Button clicked');">ログイン</button>
  </form>

  <script>
    console.log('Login page loaded');
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      console.log('Form submit event triggered');
      console.log('Action:', this.action);
      console.log('Method:', this.method);
    });
  </script>
</body>
</html>
