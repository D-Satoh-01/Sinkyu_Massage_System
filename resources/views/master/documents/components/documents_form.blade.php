{{-- resources/views/master/documents/components/documents_form.blade.php --}}

@csrf

<div class="mb-3">
  <label class="fw-semibold" for="category">文書カテゴリ <span class="text-danger">*</span></label>
  @error('category')
    <span class="text-danger ms-2">{{ $message }}</span>
  @enderror
  <br>
  <select name="category" id="category" required>
    <option value="">選択してください</option>
    @foreach($categories as $category)
      <option value="{{ $category }}" {{ old('category', $item->category ?? '') == $category ? 'selected' : '' }}>
        {{ $category }}
      </option>
    @endforeach
  </select>
</div>

<div class="mb-3">
  <label class="fw-semibold" for="name">文書テンプレート <span class="text-danger">*</span></label>
  @error('name')
    <span class="text-danger ms-2">{{ $message }}</span>
  @enderror
  <br>
  <select name="name" id="name" required>
    <option value="">選択してください</option>
    @foreach($templates as $template)
      <option value="{{ $template->id }}" {{ old('name', $item->name ?? '') == $template->id ? 'selected' : '' }}>
        {{ $template->category }} - {{ $template->name }}
      </option>
    @endforeach
  </select>
</div>

<div class="mb-3">
  <label class="fw-semibold" for="content">本文内容 <span class="text-danger">*</span></label>
  @error('content')
    <span class="text-danger ms-2">{{ $message }}</span>
  @enderror
  <br>
  <textarea name="content" id="content" required rows="6">{{ old('content', $item->content ?? '') }}</textarea>
</div>

<div class="mb-3">
  <label class="fw-semibold" for="font_size">フォントサイズ</label>
  @error('font_size')
    <span class="text-danger ms-2">{{ $message }}</span>
  @enderror
  <br>
  <input type="number" name="font_size" id="font_size" value="{{ old('font_size', $item->font_size ?? 12) }}" style="width: 100px;">
</div>

<div class="mb-3">
  <label class="fw-semibold" for="line_height">行間隔</label>
  @error('line_height')
    <span class="text-danger ms-2">{{ $message }}</span>
  @enderror
  <br>
  <input type="number" name="line_height" id="line_height" value="{{ old('line_height', $item->line_height ?? 7) }}" style="width: 100px;">
</div>

<button type="submit">{{ $submitLabel ?? '登録' }}</button>
<a href="{{ $cancelRoute ?? route('master.documents.index') }}">
  <button type="button">キャンセル</button>
</a>
