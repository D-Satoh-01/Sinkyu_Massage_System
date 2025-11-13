<!-- I:\ -->


# GitHub Copilot Custom Instructions

## 文章スタイル関連
### 共通設定
- 日本語（常体）を使用
- 特定のフレーズには以下のルールを適用
  - ~だ。 → ~。
  - 了解した。, わかった。 → OK。
  - 完了した。 → 完了。
  - ~るか？ → ~る？
  - ~する必要がある。 → ~が必要。

### Text Block (通常対話テキスト)
- －

### Thinking Block (思考内容テキスト)
- 特定のフレーズには以下のルールを適用
  - ~する。 → ~。
  - ~める。 → ~。
  - ~している。 → ~。
  - ~した。 → ~完了。
  - ~確認。 → ~チェック。
  - ~確認しよう。 → ~チェック。


## コーディング関連
- インデント：2


## その他
- Sinkyu_Massage_Systemプロジェクト関連
  - データベース関連
    - データベース接続･操作時、以下の方法を優先的に使用
      - テーブル一覧取得：`DB::select('SHOW TABLES')`
      - テーブル存在確認：`DB::connection()->getSchemaBuilder()->hasTable('テーブル名')`
      - カラム情報取得： <br>
        - `DB::select("DESCRIBE テーブル名")`
        - `DB::connection()->getSchemaBuilder()->getColumnListing('テーブル名')`        
        - `DB::connection()->getSchemaBuilder()->getColumns('テーブル名')`
      - データ取得： <br>
        - `DB::table('テーブル名')->get()`
        - `DB::table('テーブル名')->first()`
      - レコード数取得：`DB::table('テーブル名')->count()`
      - 基本情報取得： <br>
        - `DB::connection()->getDatabaseName()`
        - `DB::connection()->getDriverName()`
        - `DB::connection()->getPdo()`
    - データベース接続･操作時、以下の方法は使用禁止
      - DoctrineSchemaManager <br>
        - `DB::connection()->getDoctrineSchemaManager()`
      - Modelクラス <br>
        - `\App\Models\ClinicUser::first()`
        - `\App\Models\ClinicUser::count()`
    - データベース接続･操作時の注意点
      - `php artisan tinker --execute`：Windows環境は$記号が消えるバグが存在するため、以下の回避策を利用
        - 一時的なPHPファイルを作成して`php ファイル名.php`で実行
      - `SchemaBuilder::getTables()`や`getTableListing()`はMySQLシステムテーブルも含むため、アプリケーションテーブルのみが必要な場合は`SHOW TABLES`を使用
      - 発生しやすいエラー集
        - PHP Parse error:  syntax error, unexpected token "\"