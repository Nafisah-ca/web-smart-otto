@extends('layouts.admin')
@section('title', 'Artikel Blog')
@section('page-title', 'Artikel Blog')

@section('content')
<div class="space-y-4">

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="flex gap-2 flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari judul atau penulis…"
                   class="form-input max-w-xs text-sm">
            <select name="status" class="form-input w-auto text-sm">
                <option value="">Semua Status</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
            </select>
            <button type="submit" class="btn-secondary btn-sm">Filter</button>
            @if(request('search') || request('status'))
            <a href="{{ route('admin.posts.index') }}" class="btn-secondary btn-sm text-gray-500">Reset</a>
            @endif
        </form>
        <a href="{{ route('admin.posts.create') }}" class="btn-primary btn-sm whitespace-nowrap">+ Artikel Baru</a>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <table class="w-full table-auto">
            <thead>
                <tr>
                    <th class="w-14">Thumb</th>
                    <th>Judul</th>
                    <th class="hidden md:table-cell">Penulis</th>
                    <th class="hidden md:table-cell">Tanggal</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr class="hover:bg-gray-50 transition-colors">
                    {{-- Thumbnail --}}
                    <td class="px-4 py-3">
                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                            @if($post->thumbnail_url)
                                <img src="{{ asset($post->thumbnail_url) }}"
                                     alt="{{ $post->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                    </td>

                    {{-- Title --}}
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.posts.edit', $post) }}"
                           class="font-medium text-gray-800 hover:text-primary-700 text-sm line-clamp-2 leading-snug">
                            {{ $post->title }}
                        </a>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $post->category }}</p>
                    </td>

                    {{-- Author --}}
                    <td class="px-4 py-3 text-sm text-gray-600 hidden md:table-cell whitespace-nowrap">
                        {{ $post->author_name }}
                    </td>

                    {{-- Date --}}
                    <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell whitespace-nowrap">
                        {{ $post->published_at ? $post->published_at->format('d M Y') : '—' }}
                    </td>

                    {{-- Status --}}
                    <td class="px-4 py-3">
                        @if($post->status === 'published')
                            <span class="badge badge-green">Published</span>
                        @else
                            <span class="badge badge-gray">Draft</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                               title="Lihat di website"
                               class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                            <a href="{{ route('admin.posts.edit', $post) }}"
                               class="btn-secondary btn-sm text-xs">Edit</a>
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}"
                                  onsubmit="return confirmDelete(event, '{{ addslashes($post->title) }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-gray-400 text-sm">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v8a2 2 0 01-2 2z"/></svg>
                        Belum ada artikel.
                        <a href="{{ route('admin.posts.create') }}" class="text-primary-600 hover:underline ml-1">Buat sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($posts->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $posts->links() }}
        </div>
        @endif
    </div>

</div>

{{-- Delete confirmation modal --}}
<div id="delete-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 z-10">
        <div class="flex items-start gap-4 mb-5">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 text-base">Hapus Artikel?</h3>
                <p class="text-sm text-gray-500 mt-1">
                    Artikel "<span id="delete-title" class="font-medium text-gray-700"></span>" akan dihapus permanen beserta thumbnail-nya. Tindakan ini tidak bisa dibatalkan.
                </p>
            </div>
        </div>
        <div class="flex gap-3 justify-end">
            <button onclick="closeDeleteModal()" class="btn-secondary btn-sm">Batal</button>
            <button id="delete-confirm-btn" class="btn-danger btn-sm">Ya, Hapus</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let pendingForm = null;

function confirmDelete(e, title) {
    e.preventDefault();
    pendingForm = e.target.closest('form');
    document.getElementById('delete-title').textContent = title;
    document.getElementById('delete-modal').classList.remove('hidden');
    return false;
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    pendingForm = null;
}

document.getElementById('delete-confirm-btn').addEventListener('click', () => {
    if (pendingForm) pendingForm.submit();
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>
@endpush
@endsection
