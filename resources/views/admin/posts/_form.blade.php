{{--
    Shared form partial for create & edit.
    Variables available:
        $post  — existing Post model (on edit) or null (on create)
    The parent view must wrap this in a <form> with method, action, enctype.
--}}

@php $isEdit = isset($post) && $post->exists; @endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ── LEFT: Main content (2/3) ────────────────────────────── --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Title --}}
        <div>
            <label for="title" class="form-label">Judul Artikel <span class="text-red-500">*</span></label>
            <input id="title" name="title" type="text"
                   value="{{ old('title', $post->title ?? '') }}"
                   placeholder="Masukkan judul artikel…"
                   class="form-input text-base font-medium @error('title') border-red-400 @enderror"
                   required maxlength="255">
            @error('title')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Slug (auto-generated, editable) --}}
        <div>
            <label for="slug" class="form-label">
                Slug URL
                <span class="ml-1 text-xs text-gray-400 font-normal">(otomatis dari judul, bisa diubah manual)</span>
            </label>
            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-primary-500 focus-within:border-primary-500 bg-white">
                <span class="px-3 text-xs text-gray-400 bg-gray-50 border-r border-gray-200 py-2.5 whitespace-nowrap select-none">/blog/</span>
                <input id="slug" name="slug" type="text"
                       value="{{ old('slug', $post->slug ?? '') }}"
                       placeholder="slug-artikel"
                       class="flex-1 px-3 py-2 text-sm focus:outline-none bg-white @error('slug') border-red-400 @enderror"
                       maxlength="255">
            </div>
            @error('slug')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Excerpt --}}
        <div>
            <label for="excerpt" class="form-label">Ringkasan / Excerpt</label>
            <textarea id="excerpt" name="excerpt" rows="3"
                      placeholder="Deskripsi singkat artikel (maks. 500 karakter)…"
                      maxlength="500"
                      class="form-input @error('excerpt') border-red-400 @enderror">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
            <p class="text-xs text-gray-400 mt-1"><span id="excerpt-count">0</span>/500 karakter</p>
            @error('excerpt')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Rich Text Editor --}}
        <div>
            <label class="form-label">Konten Artikel <span class="text-red-500">*</span></label>

            {{-- Toolbar --}}
            <div class="flex flex-wrap gap-1 p-2 bg-gray-50 border border-gray-200 rounded-t-lg border-b-0">
                @foreach([
                    ['cmd'=>'bold',         'icon'=>'<strong>B</strong>',       'title'=>'Bold'],
                    ['cmd'=>'italic',       'icon'=>'<em>I</em>',               'title'=>'Italic'],
                    ['cmd'=>'underline',    'icon'=>'<u>U</u>',                 'title'=>'Garis bawah'],
                    ['cmd'=>'strikeThrough','icon'=>'<s>S</s>',                 'title'=>'Coret'],
                ] as $btn)
                <button type="button" data-cmd="{{ $btn['cmd'] }}"
                        title="{{ $btn['title'] }}"
                        class="editor-btn w-8 h-8 text-sm font-medium text-gray-600 hover:bg-white hover:text-primary-700 rounded-lg border border-transparent hover:border-gray-200 transition-colors">
                    {!! $btn['icon'] !!}
                </button>
                @endforeach

                <div class="w-px h-6 bg-gray-200 self-center mx-1"></div>

                @foreach([
                    ['cmd'=>'h2',      'label'=>'H2'],
                    ['cmd'=>'h3',      'label'=>'H3'],
                ] as $h)
                <button type="button" data-heading="{{ $h['cmd'] }}"
                        class="editor-btn px-2.5 h-8 text-xs font-bold text-gray-600 hover:bg-white hover:text-primary-700 rounded-lg border border-transparent hover:border-gray-200 transition-colors">
                    {{ $h['label'] }}
                </button>
                @endforeach

                <div class="w-px h-6 bg-gray-200 self-center mx-1"></div>

                <button type="button" data-cmd="insertUnorderedList" title="Bullet list"
                        class="editor-btn w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-white hover:text-primary-700 rounded-lg border border-transparent hover:border-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                </button>
                <button type="button" data-cmd="insertOrderedList" title="Numbered list"
                        class="editor-btn w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-white hover:text-primary-700 rounded-lg border border-transparent hover:border-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6h13M7 12h13M7 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                </button>

                <div class="w-px h-6 bg-gray-200 self-center mx-1"></div>

                <button type="button" id="btn-link" title="Tambah link"
                        class="editor-btn w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-white hover:text-primary-700 rounded-lg border border-transparent hover:border-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </button>
                <button type="button" id="btn-img" title="Sisipkan gambar (URL)"
                        class="editor-btn w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-white hover:text-primary-700 rounded-lg border border-transparent hover:border-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </button>
                <button type="button" data-cmd="insertHorizontalRule" title="Garis pemisah"
                        class="editor-btn w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-white hover:text-primary-700 rounded-lg border border-transparent hover:border-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"/></svg>
                </button>
            </div>

            {{-- Editable area --}}
            <div id="editor"
                 contenteditable="true"
                 class="min-h-[360px] border border-gray-300 rounded-b-lg p-4 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm leading-relaxed overflow-y-auto"
                 style="max-height: 600px;"
                 aria-label="Konten artikel"
                 aria-multiline="true"></div>

            {{-- Hidden textarea: konten di-pass via JS, bukan render langsung agar tag </textarea> di dalam konten tidak menutup elemen ini --}}
            <textarea id="content" name="content" class="hidden"></textarea>
            @error('content')<p class="form-error mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- SEO --}}
        <details class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden" {{ $errors->has('meta_title') || $errors->has('meta_description') ? 'open' : '' }}>
            <summary class="px-5 py-3.5 text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 transition-colors list-none flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/></svg>
                    SEO & Meta
                </span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </summary>
            <div class="px-5 pb-5 pt-3 space-y-4 border-t border-gray-200">
                <div>
                    <label for="meta_title" class="form-label">Meta Title</label>
                    <input id="meta_title" name="meta_title" type="text"
                           value="{{ old('meta_title', $post->meta_title ?? '') }}"
                           placeholder="Judul untuk mesin pencari (maks. 255 karakter)"
                           maxlength="255"
                           class="form-input @error('meta_title') border-red-400 @enderror">
                    @error('meta_title')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="meta_description" class="form-label">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" rows="3"
                              placeholder="Deskripsi untuk mesin pencari (maks. 500 karakter)"
                              maxlength="500"
                              class="form-input @error('meta_description') border-red-400 @enderror">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                    @error('meta_description')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </details>

        {{-- CTA Banner --}}
        <details class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden"
                 id="cta-details"
                 {{ (old('cta_enabled', $post->cta_enabled ?? false)) ? 'open' : '' }}>
            <summary class="px-5 py-3.5 text-sm font-semibold text-gray-700 cursor-pointer hover:bg-gray-100 transition-colors list-none flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    Banner CTA
                </span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </summary>
            <div class="px-5 pb-5 pt-3 border-t border-gray-200 space-y-4">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="cta_enabled" value="0">
                    <input id="cta_enabled" name="cta_enabled" type="checkbox" value="1"
                           {{ old('cta_enabled', $post->cta_enabled ?? false) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 h-4 w-4">
                    <label for="cta_enabled" class="text-sm font-medium text-gray-700">Tampilkan banner CTA di artikel ini</label>
                </div>
                <div>
                    <label for="cta_title" class="form-label">Judul CTA</label>
                    <input id="cta_title" name="cta_title" type="text"
                           value="{{ old('cta_title', $post->cta_title ?? 'Siap Inspeksi Kendaraan Anda?') }}"
                           maxlength="255" class="form-input">
                </div>
                <div>
                    <label for="cta_subtitle" class="form-label">Subjudul CTA</label>
                    <input id="cta_subtitle" name="cta_subtitle" type="text"
                           value="{{ old('cta_subtitle', $post->cta_subtitle ?? 'Booking inspeksi sekarang dan dapatkan laporan lengkap.') }}"
                           maxlength="500" class="form-input">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="cta_button_text" class="form-label">Teks Tombol</label>
                        <input id="cta_button_text" name="cta_button_text" type="text"
                               value="{{ old('cta_button_text', $post->cta_button_text ?? 'Booking Sekarang') }}"
                               maxlength="100" class="form-input">
                    </div>
                    <div>
                        <label for="cta_button_url" class="form-label">URL Tombol</label>
                        <input id="cta_button_url" name="cta_button_url" type="text"
                               value="{{ old('cta_button_url', $post->cta_button_url ?? '') }}"
                               placeholder="{{ route('booking.create') }}"
                               maxlength="255" class="form-input">
                    </div>
                </div>
                {{-- CTA Image --}}
                <div>
                    <label class="form-label">Gambar Banner (opsional, jpg/png/webp, maks. 2MB)</label>
                    @if($isEdit && $post->cta_image)
                    <div class="mb-2 flex items-center gap-3">
                        <img src="{{ asset($post->cta_image) }}" alt="CTA image"
                             class="w-24 h-14 object-cover rounded-lg border border-gray-200">
                        <label class="flex items-center gap-2 text-sm text-red-600 cursor-pointer">
                            <input type="checkbox" name="cta_image_remove" value="1" class="rounded border-gray-300">
                            Hapus gambar
                        </label>
                    </div>
                    @endif
                    <input type="file" name="cta_image" accept="image/jpeg,image/png,image/webp"
                           class="form-input text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>
            </div>
        </details>

    </div>

    {{-- ── RIGHT: Sidebar (1/3) ─────────────────────────────────── --}}
    <div class="space-y-5">

        {{-- Publish box --}}
        <div class="card p-5 space-y-4">
            <h3 class="text-sm font-semibold text-gray-700 border-b border-gray-100 pb-3">Penerbitan</h3>

            <div>
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status"
                        class="form-input @error('status') border-red-400 @enderror">
                    <option value="draft"     {{ old('status', $post->status ?? 'draft') === 'draft'     ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $post->status ?? 'draft') === 'published' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="published_at" class="form-label">Tanggal Terbit</label>
                <input id="published_at" name="published_at" type="datetime-local"
                       value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                       class="form-input @error('published_at') border-red-400 @enderror">
                @error('published_at')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="flex flex-col gap-2 pt-1">
                <button type="submit" class="btn-primary w-full justify-center">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Terbitkan Artikel' }}
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn-secondary btn-sm w-full justify-center text-center">Batal</a>
            </div>
        </div>

        {{-- Author --}}
        <div class="card p-5">
            <label for="author_name" class="form-label">Penulis <span class="text-red-500">*</span></label>
            <input id="author_name" name="author_name" type="text"
                   value="{{ old('author_name', $post->author_name ?? 'Tim Smart Otto') }}"
                   maxlength="100"
                   class="form-input @error('author_name') border-red-400 @enderror"
                   required>
            @error('author_name')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Category --}}
        <div class="card p-5">
            <label for="category" class="form-label">Kategori</label>
            <input id="category" name="category" type="text"
                   value="{{ old('category', $post->category ?? 'Inspeksi Kendaraan') }}"
                   maxlength="100" placeholder="mis. Inspeksi Kendaraan"
                   class="form-input">
        </div>

        {{-- Thumbnail --}}
        <div class="card p-5 space-y-3">
            <h3 class="text-sm font-semibold text-gray-700">Thumbnail</h3>
            <p class="text-xs text-gray-400">jpg / png / webp · maks. 2MB</p>

            {{-- Preview --}}
            <div id="thumb-preview-wrap" class="{{ ($isEdit && $post->thumbnail_url) ? '' : 'hidden' }} relative rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                <img id="thumb-preview"
                     src="{{ $isEdit && $post->thumbnail_url ? asset($post->thumbnail_url) : '' }}"
                     alt="Preview thumbnail"
                     class="w-full object-cover" style="max-height:160px;">
            </div>

            <input type="file" id="thumbnail" name="thumbnail"
                   accept="image/jpeg,image/png,image/webp"
                   class="form-input text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
            @error('thumbnail')<p class="form-error">{{ $message }}</p>@enderror

            @if($isEdit && $post->thumbnail_url)
            <label class="flex items-center gap-2 text-xs text-red-600 cursor-pointer mt-1">
                <input type="checkbox" name="thumbnail_remove" value="1"
                       class="rounded border-gray-300 text-red-600 focus:ring-red-400">
                Hapus thumbnail
            </label>
            @endif
        </div>

    </div>
</div>

@push('scripts')
<script>
/* ── Slug auto-generate from title ─────────────────────────── */
const titleInput = document.getElementById('title');
const slugInput  = document.getElementById('slug');
let slugManual   = {{ $isEdit ? 'true' : 'false' }};

function slugify(str) {
    return str.toLowerCase()
        .replace(/[àáâãäå]/g,'a').replace(/[èéêë]/g,'e')
        .replace(/[ìíîï]/g,'i').replace(/[òóôõö]/g,'o')
        .replace(/[ùúûü]/g,'u').replace(/[ñ]/g,'n')
        .replace(/[^a-z0-9\s-]/g,'')
        .trim().replace(/\s+/g,'-').replace(/-+/g,'-');
}

if (titleInput && slugInput) {
    titleInput.addEventListener('input', () => {
        if (!slugManual) {
            slugInput.value = slugify(titleInput.value);
        }
    });
    slugInput.addEventListener('input', () => { slugManual = true; });
}

/* ── Excerpt character counter ──────────────────────────────── */
const excerptEl = document.getElementById('excerpt');
const countEl   = document.getElementById('excerpt-count');
function updateCount() { if(countEl) countEl.textContent = (excerptEl?.value || '').length; }
if (excerptEl) { excerptEl.addEventListener('input', updateCount); updateCount(); }

/* ── Thumbnail preview ──────────────────────────────────────── */
const thumbInput   = document.getElementById('thumbnail');
const thumbPreview = document.getElementById('thumb-preview');
const thumbWrap    = document.getElementById('thumb-preview-wrap');
if (thumbInput) {
    thumbInput.addEventListener('change', e => {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = ev => {
            thumbPreview.src = ev.target.result;
            thumbWrap.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });
}

/* ── Rich text editor ───────────────────────────────────────── */
const editor  = document.getElementById('editor');
const content = document.getElementById('content');

// Load existing content — inject via JSON to safely handle any HTML
// including tags that would break a raw textarea value
const _initialContent = {!! json_encode(old('content', $post->content ?? '')) !!};
if (editor && content) {
    editor.innerHTML = _initialContent;
    content.value    = _initialContent;
}

// Sync on every input
editor?.addEventListener('input', () => { content.value = editor.innerHTML; });

// Format buttons
document.querySelectorAll('.editor-btn[data-cmd]').forEach(btn => {
    btn.addEventListener('click', () => {
        editor.focus();
        document.execCommand(btn.dataset.cmd, false, null);
        content.value = editor.innerHTML;
    });
});

// Heading buttons
document.querySelectorAll('.editor-btn[data-heading]').forEach(btn => {
    btn.addEventListener('click', () => {
        editor.focus();
        document.execCommand('formatBlock', false, '<' + btn.dataset.heading + '>');
        content.value = editor.innerHTML;
    });
});

// Link
document.getElementById('btn-link')?.addEventListener('click', () => {
    const url = prompt('URL tautan:');
    if (url) {
        editor.focus();
        document.execCommand('createLink', false, url);
        content.value = editor.innerHTML;
    }
});

// Image by URL
document.getElementById('btn-img')?.addEventListener('click', () => {
    const url = prompt('URL gambar:');
    if (url) {
        editor.focus();
        document.execCommand('insertImage', false, url);
        content.value = editor.innerHTML;
    }
});

// Prevent form submit with Enter inside editor
editor?.addEventListener('keydown', e => {
    if (e.key === 'Enter' && e.shiftKey) {
        e.preventDefault();
        document.execCommand('insertLineBreak');
    }
});

// Final sync before submit
editor?.closest('form')?.addEventListener('submit', () => {
    content.value = editor.innerHTML;
});
</script>
@endpush
