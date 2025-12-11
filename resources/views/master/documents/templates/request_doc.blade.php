{{-- resources/views/document_templates/consent_request.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>蜷梧э譖ｸ萓晞ｼ</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    body {
      line-height: {{ $document->line_height ?? 7 }}mm;
      font-size: {{ $document->font_size ?? 12 }}pt;
    }
  </style>
</head>
<body class="pdf-request-doc">
  <div class="title">蠕｡ 萓・鬆ｼ 譖ｸ</div>

  <div class="date">
    {{ date('Y蟷ｴ m譛・d譌･', strtotime($document->created_at)) }}
  </div>

  <div class="recipient">
    蠕｡諡・ｽ灘現 蠕｡譛ｺ荳・  </div>

  <div class="content-area">{{ $document->content ?? '' }}</div>

  <div class="handwrite-area">
    險・br>
    豌丞錐・・br>
    逋ｺ逞・ｼ・  </div>

  <div class="clinic-info">
    縲・{{ $clinicInfo->postal_code ?? '驛ｵ萓ｿ逡ｪ蜿ｷ' }}{{ $clinicInfo->address_1 ?? '菴乗園' }}{{ $clinicInfo->address_2 ?? '' }}{{ $clinicInfo->address_3 ?? '' }}<br>
    TEL・嘴{ $clinicInfo->phone ?? '髮ｻ隧ｱ逡ｪ蜿ｷ' }}<br>
    {{ $clinicInfo->clinic_name ?? '莠区･ｭ謇蜷・ }}<br>
    {{ $clinicInfo->owner_name ?? '莉｣陦ｨ閠・錐' }}
  </div>
</body>
</html>
