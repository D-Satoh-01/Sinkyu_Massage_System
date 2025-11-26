<?php
// app/Support/Breadcrumbs.php

namespace App\Support;

class Breadcrumbs
{
  protected static $definitions = [];

  /**
   * パンくずリストの定義を登録
   *
   * @param string $name 定義名（通常はルート名）
   * @param callable $callback パンくずリスト配列を返すコールバック
   */
  public static function define(string $name, callable $callback): void
  {
    static::$definitions[$name] = $callback;
  }

  /**
   * パンくずリストを生成
   *
   * @param string $name 定義名
   * @param mixed ...$params コールバックに渡すパラメータ
   * @return array パンくずリスト配列
   */
  public static function generate(string $name, ...$params): array
  {
    if (!isset(static::$definitions[$name])) {
      return [];
    }

    return call_user_func(static::$definitions[$name], ...$params);
  }

  /**
   * 定義をクリア（主にテスト用）
   */
  public static function clear(): void
  {
    static::$definitions = [];
  }
}
