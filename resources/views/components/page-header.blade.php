<!-- resources/views/components/page-header.blade.php -->

@props(['title', 'breadcrumbs' => []])

<div class="page-header" style="margin-bottom: 24px;">
  <h4 style="margin: 0 0 8px 0; font-size: 1.5rem; font-weight: 600; color: #1f2937;">
    {{ $title }}
  </h4>

  @if(count($breadcrumbs) > 0)
  <nav aria-label="breadcrumb">
    <ol style="list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 0px; font-size: 0.9rem; color: #6b7280;">
      @foreach($breadcrumbs as $index => $breadcrumb)
        <li style="display: flex; align-items: center; gap: 8px;">
          @if(isset($breadcrumb['url']))
            <a href="{{ $breadcrumb['url'] }}" style="font-weight: 500">
              {{ $breadcrumb['label'] }}
            </a>
          @else
            <span style="color: #6b7280;">{{ $breadcrumb['label'] }}</span>
          @endif

          @if($index < count($breadcrumbs) - 1)
            <span style="color: #555;">〉</span>
          @endif
        </li>
      @endforeach
    </ol>
  </nav>
  @endif

  <hr class="border border-black border-1">
</div>
