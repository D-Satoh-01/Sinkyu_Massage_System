<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;

/**
 * セッション確認画面処理トレイト
 * 
 * 確認画面の表示とセッション管理の共通処理を提供する。
 * 全てのエンティティの登録・編集・複製フローで使用される。
 */
trait SessionConfirmationTrait
{
    /**
     * セッションにデータを保存して確認画面を表示
     *
     * @param Request $request
     * @param array $validated バリデーション済みデータ
     * @param string $sessionKey セッションキー
     * @param array $labels フィールドラベルの配列
     * @param string $backRoute 戻るボタンのルート名
     * @param string $storeRoute 登録ボタンのルート名
     * @param string $pageTitle ページタイトル
     * @param string $message 確認メッセージ
     * @param mixed $backId 戻る時の第1パラメータ
     * @param mixed $backSecondaryId 戻る時の第2パラメータ
     * @return \Illuminate\View\View
     */
    protected function showConfirmation(
        Request $request,
        array $validated,
        string $sessionKey,
        array $labels,
        string $backRoute,
        string $storeRoute,
        string $pageTitle,
        string $message,
        $backId = null,
        $backSecondaryId = null
    ) {
        $request->session()->put($sessionKey, $validated);

        $viewData = [
            'data' => $validated,
            'labels' => $labels,
            'back_route' => $backRoute,
            'store_route' => $storeRoute,
            'page_title' => $pageTitle,
            'registration_message' => $message,
        ];

        // 戻るルートのパラメータを設定
        if ($backId !== null) {
            $viewData['back_id'] = $backId;
        }
        
        if ($backSecondaryId !== null) {
            // 2番目のIDパラメータ（insurance_id, history_id, plan_id等）
            $viewData['back_history_id'] = $backSecondaryId;
            $viewData['back_insurance_id'] = $backSecondaryId;
            $viewData['back_plan_id'] = $backSecondaryId;
        }

        return view('registration-review', $viewData);
    }

    /**
     * セッションからデータを取得（エラー時はリダイレクト）
     *
     * @param Request $request
     * @param string $sessionKey セッションキー
     * @param string $redirectRoute リダイレクト先のルート名
     * @param mixed ...$routeParams ルートパラメータ
     * @return array|null データ配列またはnull
     */
    protected function getSessionDataOrFail(
        Request $request,
        string $sessionKey,
        string $redirectRoute,
        ...$routeParams
    ) {
        $data = $request->session()->get($sessionKey);

        if (!$data) {
            throw new \RuntimeException(
                'セッションが切れました。もう一度入力してください。'
            );
        }

        return $data;
    }

    /**
     * セッションをクリア
     *
     * @param Request $request
     * @param string $sessionKey セッションキー
     * @return void
     */
    protected function clearSession(Request $request, string $sessionKey)
    {
        $request->session()->forget($sessionKey);
    }
}
