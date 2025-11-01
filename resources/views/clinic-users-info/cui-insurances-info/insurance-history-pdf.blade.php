<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>医療保険情報履歴一覧表</title>
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
    .checkbox {
      text-align: center;
      font-size: 12px;
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
  <h1>医療保険情報履歴一覧表</h1>
  <p>利用者名: {{ $user->clinic_user_name }}</p>

  <table>
    <thead>
      <tr>
        <th style="width: 5%;">状態</th>
        <th style="width: 8%;">保険区分</th>
        <th style="width: 10%;">被保険者番号</th>
        <th style="width: 8%;">資格取得年月日</th>
        <th style="width: 8%;">認定年月日</th>
        <th style="width: 8%;">発行年月日</th>
        <th style="width: 7%;">一部負担金の割合</th>
        <th style="width: 8%;">有効期限</th>
        <th style="width: 8%;">世帯主氏名</th>
        <th style="width: 8%;">被保険者氏名</th>
        <th style="width: 5%;">医療助成対象</th>
        <th style="width: 8%;">公費負担者番号</th>
        <th style="width: 8%;">公費受給者番号</th>
        <th style="width: 8%;">保険者番号</th>
        <th style="width: 8%;">保険者名称</th>
      </tr>
    </thead>
    <tbody>
      @forelse($insurances as $index => $insurance)
      <tr>
        <td class="@if($index === 0) status-latest @else status-updated @endif">
          {{ $index === 0 ? '最新' : '更新済み' }}
        </td>
        <td>
          @php
            $insurerNumberLength = strlen($insurance->insurer?->insurer_number ?? '');
          @endphp
          @if($insurerNumberLength == 6)
            国民健康保険
          @elseif($insurerNumberLength == 8)
            組合保険
          @else
            保険
          @endif
        </td>
        <td>{{ $insurance->insured_number }}</td>
        <td>
          @if($insurance->license_acquisition_date)
            {{ $insurance->license_acquisition_date->format('Y/m/d') }}
          @endif
        </td>
        <td>
          @if($insurance->certification_date)
            {{ $insurance->certification_date->format('Y/m/d') }}
          @endif
        </td>
        <td>
          @if($insurance->issue_date)
            {{ $insurance->issue_date->format('Y/m/d') }}
          @endif
        </td>
        <td style="text-align: center;">
          {{ $insurance->copayment_rate }}
        </td>
        <td>
          @if($insurance->expiry_date)
            {{ $insurance->expiry_date->format('Y/m/d') }}
          @endif
        </td>
        <td>
          @if($insurance->relationship !== '本人')
            {{ $insurance->insured_name }}
          @endif
        </td>
        <td>
          @if($insurance->relationship === '本人')
            {{ $insurance->insured_name }}
          @endif
        </td>
        <td class="checkbox">
          @if($insurance->is_healthcare_subsidized)
            ☑
          @else
            ☐
          @endif
        </td>
        <td>{{ $insurance->locality_code ?? $insurance->public_funds_payer_code }}</td>
        <td>{{ $insurance->recipient_code ?? $insurance->public_funds_recipient_code }}</td>
        <td>{{ $insurance->insurer?->insurer_number }}</td>
        <td>{{ $insurance->insurer?->insurer_name }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="15" style="text-align: center;">データがありません</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
