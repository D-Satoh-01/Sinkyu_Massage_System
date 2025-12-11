<!-- resources/views/clinic-users/plans/plans_pdf.blade.php -->

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>險育判諠・ｱ螻･豁ｴ荳隕ｧ陦ｨ</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="pdf-plans">
  <h1>險育判諠・ｱ螻･豁ｴ荳隕ｧ陦ｨ</h1>
  <div class="user-info">
    <strong>蛻ｩ逕ｨ閠・錐:</strong> {{ $user->clinic_user_name }}
  </div>

  @forelse($planInfos as $index => $plan)
    <div class="plan-record">
      <div class="plan-header">
        <span class="@if($index === 0) status-latest @else status-updated @endif">
          {{ $index === 0 ? '縲先怙譁ｰ縲・ : '縲先峩譁ｰ貂医∩縲・ }}
        </span>
        隧穂ｾ｡譌･: {{ $plan->assessment_date?->format('Y蟷ｴm譛・譌･') ?? '譛ｪ險ｭ螳・ }}
        @if($plan->assessor)
          / 隧穂ｾ｡閠・ {{ $plan->assessor }}
        @endif
      </div>

      <!-- 蝓ｺ譛ｬ諠・ｱ -->
      <table>
        <tr>
          <th class="label-cell">隧穂ｾ｡譌･</th>
          <td class="value-cell">{{ $plan->assessment_date?->format('Y蟷ｴm譛・譌･') ?? '' }}</td>
          <th class="label-cell">隧穂ｾ｡閠・/th>
          <td class="value-cell">{{ $plan->assessor ?? '' }}</td>
        </tr>
        <tr>
          <th class="label-cell">逍ｾ蜻ｼ蜷ｸ</th>
          <td class="value-cell" colspan="3">{{ $plan->audience ?? '' }}</td>
        </tr>
        <tr>
          <th class="label-cell">譛ｬ莠ｺ繝ｻ螳ｶ譌丞酔諢乗律</th>
          <td class="value-cell" colspan="3">{{ $plan->user_and_family_consent_date?->format('Y蟷ｴm譛・譌･') ?? '' }}</td>
        </tr>
      </table>

      <!-- ADL鬆・岼 -->
      <table class="adl-table">
        <thead>
          <tr>
            <th class="section-title" colspan="3">ADL・域律蟶ｸ逕滓ｴｻ蜍穂ｽ懶ｼ芽ｩ穂ｾ｡</th>
          </tr>
          <tr>
            <th>鬆・岼</th>
            <th>莉句勧繝ｬ繝吶Ν</th>
            <th>蛯呵・/th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th class="label-cell">鬟滉ｺ・/th>
            <td class="level">{{ $plan->eatingAssistanceLevel?->assistance_level ?? '' }}</td>
            <td class="note textarea-cell">{{ $plan->eating_assistance_note ?? '' }}</td>
          </tr>
          <tr>
            <th class="label-cell">襍ｷ螻・ｧｻ蜍・/th>
            <td class="level">{{ $plan->movingAssistanceLevel?->assistance_level ?? '' }}</td>
            <td class="note textarea-cell">{{ $plan->moving_assistance_note ?? '' }}</td>
          </tr>
          <tr>
            <th class="label-cell">謨ｴ螳ｹ</th>
            <td class="level">{{ $plan->personalGroomingAssistanceLevel?->assistance_level ?? '' }}</td>
            <td class="note textarea-cell">{{ $plan->personal_grooming_assistance_note ?? '' }}</td>
          </tr>
          <tr>
            <th class="label-cell">繝医う繝ｬ</th>
            <td class="level">{{ $plan->usingToiletAssistanceLevel?->assistance_level ?? '' }}</td>
            <td class="note textarea-cell">{{ $plan->using_toilet_assistance_note ?? '' }}</td>
          </tr>
          <tr>
            <th class="label-cell">蜈･豬ｴ</th>
            <td class="level">{{ $plan->bathingAssistanceLevel?->assistance_level ?? '' }}</td>
            <td class="note textarea-cell">{{ $plan->bathing_assistance_note ?? '' }}</td>
          </tr>
          <tr>
            <th class="label-cell">蟷ｳ蝨ｰ豁ｩ陦・/th>
            <td class="level">{{ $plan->walkingAssistanceLevel?->assistance_level ?? '' }}</td>
            <td class="note textarea-cell">{{ $plan->walking_assistance_note ?? '' }}</td>
          </tr>
          <tr>
            <th class="label-cell">髫取ｮｵ譏・剄</th>
            <td class="level">{{ $plan->usingStairsAssistanceLevel?->assistance_level ?? '' }}</td>
            <td class="note textarea-cell">{{ $plan->using_stairs_assistance_note ?? '' }}</td>
          </tr>
          <tr>
            <th class="label-cell">譖ｴ陦｣</th>
            <td class="level">{{ $plan->changingClothesAssistanceLevel?->assistance_level ?? '' }}</td>
            <td class="note textarea-cell">{{ $plan->changing_clothes_assistance_note ?? '' }}</td>
          </tr>
          <tr>
            <th class="label-cell">謗剃ｾｿ</th>
            <td class="level">{{ $plan->defecationAssistanceLevel?->assistance_level ?? '' }}</td>
            <td class="note textarea-cell">{{ $plan->defecation_assistance_note ?? '' }}</td>
          </tr>
          <tr>
            <th class="label-cell">謗貞ｰｿ</th>
            <td class="level">{{ $plan->urinationAssistanceLevel?->assistance_level ?? '' }}</td>
            <td class="note textarea-cell">{{ $plan->urination_assistance_note ?? '' }}</td>
          </tr>
        </tbody>
      </table>

      <!-- 縺昴・莉悶・諠・ｱ -->
      <table>
        <tr>
          <th class="label-cell">繧ｳ繝溘Η繝九こ繝ｼ繧ｷ繝ｧ繝ｳ</th>
          <td class="value-cell textarea-cell">{{ $plan->communication_note ?? '' }}</td>
        </tr>
        <tr>
          <th class="label-cell">縺疲悽莠ｺ繝ｻ縺泌ｮｶ譌上・蟶梧悍</th>
          <td class="value-cell textarea-cell">{{ $plan->wish_of_user_and_familiy ?? '' }}</td>
        </tr>
        <tr>
          <th class="label-cell">豐ｻ逋ら岼逧・/th>
          <td class="value-cell textarea-cell">{{ $plan->care_purpose ?? '' }}</td>
        </tr>
        <tr>
          <th class="label-cell">繝ｪ繝上ン繝ｪ繝・・繧ｷ繝ｧ繝ｳ繝励Ο繧ｰ繝ｩ繝</th>
          <td class="value-cell textarea-cell">{{ $plan->rehabilitation_program ?? '' }}</td>
        </tr>
        <tr>
          <th class="label-cell">閾ｪ螳・〒縺ｮ繝ｪ繝上ン繝ｪ繝・・繧ｷ繝ｧ繝ｳ</th>
          <td class="value-cell textarea-cell">{{ $plan->home_rehabilitation ?? '' }}</td>
        </tr>
        <tr>
          <th class="label-cell">蜑榊屓險育判譖ｸ菴懈・譎ゅ°繧峨・謾ｹ蝟・・螟牙喧</th>
          <td class="value-cell textarea-cell">{{ $plan->change_since_previous_planning ?? '' }}</td>
        </tr>
        <tr>
          <th class="label-cell">髫懷ｮｳ繝ｻ豕ｨ諢丈ｺ矩・/th>
          <td class="value-cell textarea-cell">{{ $plan->note ?? '' }}</td>
        </tr>
      </table>
    </div>

    @if(!$loop->last)
      <div style="page-break-after: always;"></div>
    @endif
  @empty
    <p style="text-align: center; padding: 20px;">險育判諠・ｱ縺檎匳骭ｲ縺輔ｌ縺ｦ縺・∪縺帙ｓ縲・/p>
  @endforelse
</body>
</html>
