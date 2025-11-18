{{-- resources/views/master/documents/components/documents_form.blade.php --}}

@csrf

<div class="mb-3">
  <label class="fw-semibold" for="category">文書カテゴリ</span></label><br>
  <select name="category" id="category">
    <option value="">╌╌╌</option>
    @foreach($categories as $category)
      <option value="{{ $category }}" {{ old('category', $item->category ?? '') == $category ? 'selected' : '' }}>
        {{ $category }}
      </option>
    @endforeach
  </select>
  @error('category')
    <div class="text-danger">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label class="fw-semibold" for="name">文書名称</label><br>
  <input type="text" name="name" id="name" value="{{ old('name', $item->name ?? '') }}" placeholder="文書名称を入力…">
  @error('name')
    <div class="text-danger">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label class="fw-semibold" for="content">本文</span></label><br>
  <textarea name="content" id="content" rows="6" maxlength="2000">{{ old('content', $item->content ?? '') }}</textarea>
  @error('content')
    <div class="text-danger">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label class="fw-semibold" for="font_size">フォントサイズ</label><br>
  <input type="number" name="font_size" id="font_size" value="{{ old('font_size', $item->font_size ?? 12) }}" style="width: 100px;">
  @error('font_size')
    <div class="text-danger">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3">
  <label class="fw-semibold" for="line_height">行間隔</label><br>
  <input type="number" name="line_height" id="line_height" value="{{ old('line_height', $item->line_height ?? 7) }}" style="width: 100px;">
  @error('line_height')
    <div class="text-danger">{{ $message }}</div>
  @enderror
</div>

<button type="submit">{{ $submitLabel ?? '登録' }}</button>
<a href="{{ $cancelRoute ?? route('master.documents.index') }}">
  <button type="button">キャンセル</button>
</a>
