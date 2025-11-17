<x-app-layout>
  <h2>サブマスター登録</h2><br><br>

  ・<a href="{{ route('submaster.medical-institutions') }}">医療機関名［{{ $counts['medical_institutions'] }}件］</a><br>
  ・<a href="{{ route('submaster.service-providers') }}">サービス事業者名［{{ $counts['service_providers'] }}件］</a><br>
  ・<a href="{{ route('submaster.conditions') }}">発病負傷経過（あんま・マッサージ）［{{ $counts['conditions'] }}件］</a><br>
  ・<a href="{{ route('submaster.illnesses-massage') }}">傷病名（あんま・マッサージ）［{{ $counts['illnesses_massage'] }}件］</a>
</x-app-layout>
