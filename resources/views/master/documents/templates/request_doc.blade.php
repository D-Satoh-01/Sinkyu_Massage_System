{{-- resources/views/document_templates/consent_request.blade.php --}}
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>同意書依頼</title>
  <style>
    body {
      font-family: 'MS Mincho', serif;
      line-height: {{ $document->line_height ?? 7 }}mm;
      font-size: {{ $document->font_size ?? 12 }}pt;
      margin: 40px;
    }
    .title {
      font-size: 26pt;
      text-align: center;
      margin-bottom: 30px;
      letter-spacing: 0.5em;
    }
    .date {
      text-align: right;
      margin-bottom: 20px;
    }
    .recipient {
      margin-bottom: 30px;
    }
    .content-area {
      margin: 40px 0;
      min-height: 200px;
      white-space: pre-wrap;
    }
    .divider {
      text-align: center;
      margin: 30px 0;
    }
    .patient-info {
      margin: 20px 0;
    }
    .clinic-info {
      text-align: right;
      margin-top: 40px;
    }
  </style>
</head>
<body>
  <div class="title">御 依 頼 書</div>

  <div class="date">
    &lt;{{ date('Y年 m月 d日', strtotime($document->created_at)) }}&gt;
  </div>

  <div class="recipient">
    御担当医 御机下
  </div>

  <div class="content-area">
    &lt;本文開始行&gt;

{{ $document->content ?? '' }}

    &lt;本文終了行&gt;
  </div>

  <div class="divider">記</div>

  <div class="patient-info">
    氏名：<br>
    発病：
  </div>

  <div class="clinic-info">
    〒 &lt;{{ $clinicInfo->postal_code ?? '郵便番号' }}&gt; &lt;{{ $clinicInfo->address_1 ?? '住所' }}{{ $clinicInfo->address_2 ?? '' }}{{ $clinicInfo->address_3 ?? '' }}&gt;<br>
    TEL：&lt;{{ $clinicInfo->phone ?? '電話番号' }}&gt;<br>
    &lt;{{ $clinicInfo->clinic_name ?? '事業所名' }}&gt;<br>
    &lt;{{ $clinicInfo->owner_name ?? '代表者名' }}&gt;
  </div>
</body>
</html>
