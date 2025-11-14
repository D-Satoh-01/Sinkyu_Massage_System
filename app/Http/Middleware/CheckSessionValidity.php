<?php
//-- app/Http/Middleware/CheckSessionValidity.php --//

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionValidity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 認証済みユーザーの場合のみチェック
        if (Auth::check()) {
            $sessionRemember = $request->session()->get('remember_login', false);

            // 「ログイン状態を保持」していない場合のみチェック
            if (!$sessionRemember) {
                // ログイン画面にアクセスした場合、セッションを無効化
                if ($request->is('login')) {
                    Log::info('Logged out due to accessing login page without remember');
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('login');
                }

                // 外部URLへのアクセスを検出
                // リファラーをチェック（現在のホストと比較）
                $referer = $request->header('referer');
                if ($referer) {
                    $refererHost = parse_url($referer, PHP_URL_HOST);
                    $currentHost = $request->getHost();

                    // リファラーのホストが現在のホストと異なる場合、外部サイトからのアクセス
                    if ($refererHost && $refererHost !== $currentHost) {
                        Log::info('Logged out due to external referrer', [
                            'referer' => $referer,
                            'referer_host' => $refererHost,
                            'current_host' => $currentHost
                        ]);
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                        return redirect()->route('login');
                    }
                }
            }
        }

        return $next($request);
    }
}
