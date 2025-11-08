<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>同意医師履歴一覧表（あんま・マッサージ）</title>
  <style>
    body {
      font-family: 'mplus1medium', sans-serif;
      font-size: 9px;
      margin: 0;
      padding: 0;
    }
    h1 {
      text-align: center;
      font-size: 16px;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 8px;
    }
    th, td {
      border: 1px solid #000;
      padding: 4px;
      text-align: left;
      vertical-align: middle;
    }
    th {
      background-color: #f0f0f0;
      text-align: center;
    }
    .status-latest {
      font-weight: bold;
      color: #ff0000;
    }
    .status-updated {
      color: #666666;
    }
  </style>
</head>
<body>
  <h1>同意医師履歴一覧表（あんま・マッサージ）</h1>
  <p>利用者名: {{ $user->clinic_user_name }}</p>

  <table>
    <thead>
      <tr>
        <th style="width: 6%;">状態</th>
        <th style="width: 15%;">同意医師名</th>
        <th style="width: 10%;">同意日</th>
        <th style="width: 10%;">同意開始日</th>
        <th style="width: 10%;">同意終了日</th>
        <th style="width: 10%;">給付期間開始日</th>
        <th style="width: 10%;">給付期間終了日</th>
        <th style="width: 10%;">初療日</th>
        <th style="width: 10%;">再同意期限</th>
        <th style="width: 9%;">登録日</th>
      </tr>
    </thead>
    <tbody>
      @forelse($histories as $index => $history)
      <tr>
        <td style="width: 6%;" class="@if($index === 0) status-latest @else status-updated @endif">
          {{ $index === 0 ? '最新' : '履歴' }}
        </td>
        <td style="width: 15%;">{{ $history->consenting_doctor_name }}</td>
        <td style="width: 10%;">
          @if($history->consenting_date)
            {{ \Carbon\Carbon::parse($history->consenting_date)->format('Y/m/d') }}
          @endif
        </td>
        <td style="width: 10%;">
          @if($history->consenting_start_date)
            {{ \Carbon\Carbon::parse($history->consenting_start_date)->format('Y/m/d') }}
          @endif
        </td>
        <td style="width: 10%;">
          @if($history->consenting_end_date)
            {{ \Carbon\Carbon::parse($history->consenting_end_date)->format('Y/m/d') }}
          @endif
        </td>
        <td style="width: 10%;">
          @if($history->benefit_period_start_date)
            {{ \Carbon\Carbon::parse($history->benefit_period_start_date)->format('Y/m/d') }}
          @endif
        </td>
        <td style="width: 10%;">
          @if($history->benefit_period_end_date)
            {{ \Carbon\Carbon::parse($history->benefit_period_end_date)->format('Y/m/d') }}
          @endif
        </td>
        <td style="width: 10%;">
          @if($history->first_care_date)
            {{ \Carbon\Carbon::parse($history->first_care_date)->format('Y/m/d') }}
          @endif
        </td>
        <td style="width: 10%;">
          @if($history->reconsenting_expiry)
            {{ \Carbon\Carbon::parse($history->reconsenting_expiry)->format('Y/m/d') }}
          @endif
        </td>
        <td style="width: 9%;">
          {{ \Carbon\Carbon::parse($history->created_at)->format('Y/m/d') }}
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="10" style="text-align: center;">データがありません</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
