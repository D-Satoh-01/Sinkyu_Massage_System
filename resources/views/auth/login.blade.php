<form method="POST" action="{{ route('login') }}">
    @csrf

    <div>
        <label for="login_id">ユーザーID</label>
        <input id="login_id" type="text" name="login_id" value="{{ old('login_id') }}" required autofocus>
    </div>

    <div>
        <label for="password">パスワード</label>
        <input id="password" type="password" name="password" required>
    </div>

    <div>
        <label>
            <input type="checkbox" name="remember"> ログイン状態を保持
        </label>
    </div>

    <button type="submit">ログイン</button>
</form>
