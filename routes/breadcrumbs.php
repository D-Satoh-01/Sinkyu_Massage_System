<?php
// routes/breadcrumbs.php

use App\Support\Breadcrumbs;

// ホーム
Breadcrumbs::define('index', function() {
  return [
    ['url' => route('index'), 'label' => 'ホーム'],
  ];
});

// マスター登録
Breadcrumbs::define('master.index', function() {
  return [
    ['url' => route('index'), 'label' => 'ホーム'],
    ['url' => route('master.index'), 'label' => 'マスター登録'],
  ];
});

// 医師情報 - 一覧
Breadcrumbs::define('doctors.index', function() {
  return [
    ['url' => route('index'), 'label' => 'ホーム'],
    ['url' => route('master.index'), 'label' => 'マスター登録'],
    ['url' => route('doctors.index'), 'label' => '医師情報'],
  ];
});

// 医師情報 - 新規登録
Breadcrumbs::define('doctors.create', function() {
  return [
    ['url' => route('index'), 'label' => 'ホーム'],
    ['url' => route('master.index'), 'label' => 'マスター登録'],
    ['url' => route('doctors.index'), 'label' => '医師情報'],
    ['url' => null, 'label' => '新規登録'],
  ];
});

// 医師情報 - 編集
Breadcrumbs::define('doctors.edit', function($doctorId = null) {
  return [
    ['url' => route('index'), 'label' => 'ホーム'],
    ['url' => route('master.index'), 'label' => 'マスター登録'],
    ['url' => route('doctors.index'), 'label' => '医師情報'],
    ['url' => null, 'label' => '編集'],
  ];
});

// 医師情報 - 複製
Breadcrumbs::define('doctors.duplicate', function($doctorId = null) {
  return [
    ['url' => route('index'), 'label' => 'ホーム'],
    ['url' => route('master.index'), 'label' => 'マスター登録'],
    ['url' => route('doctors.index'), 'label' => '医師情報'],
    ['url' => null, 'label' => '複製'],
  ];
});
