@extends('layouts.app')

@section('title', $issue ? 'Edit Issue' : 'Tambah Issue')

@section('content')
    <div class="mx-auto max-w-5xl px-4 py-8 md:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.3em] text-slate-500">Issue board</p>
                <h1 class="mt-2 text-3xl font-black">{{ $issue ? 'Edit Issue' : 'Tambah Issue' }}</h1>
            </div>
            <a href="{{ route('issues.index') }}" class="rounded-xl border-4 border-slate-900 bg-slate-200 px-4 py-2 text-sm font-black shadow-[3px_3px_0_#111827]">
                Kembali
            </a>
        </div>

        <div class="rounded-[28px] border-4 border-slate-900 bg-white p-6 shadow-[8px_8px_0_#111827]">
            @if ($errors->any())
                <div class="mb-5 rounded-2xl border-4 border-rose-700 bg-rose-100 p-4 text-sm font-bold text-rose-800">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="issueForm" action="{{ $issue ? route('issues.update', $issue) : route('issues.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-6">
                @csrf
                @if($issue)
                    @method('PUT')
                @endif

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="department_id" class="mb-2 block text-sm font-black uppercase tracking-wide text-slate-800">Departemen</label>
                        <select id="department_id" name="department_id" class="w-full rounded-xl border-4 border-slate-900 bg-slate-50 px-4 py-3 text-sm font-medium focus:bg-white" required>
                            <option value="">Pilih departemen</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id', $issue?->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="status" class="mb-2 block text-sm font-black uppercase tracking-wide text-slate-800">Status</label>
                        <select id="status" name="status" class="w-full rounded-xl border-4 border-slate-900 bg-slate-50 px-4 py-3 text-sm font-medium focus:bg-white" required>
                            <option value="todo" {{ old('status', $issue?->status) === 'todo' ? 'selected' : '' }}>To Do</option>
                            <option value="progress" {{ old('status', $issue?->status) === 'progress' ? 'selected' : '' }}>Progress</option>
                            <option value="done" {{ old('status', $issue?->status) === 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="title" class="mb-2 block text-sm font-black uppercase tracking-wide text-slate-800">Judul Issue</label>
                        <input id="title" name="title" type="text" value="{{ old('title', $issue?->title) }}" class="w-full rounded-xl border-4 border-slate-900 bg-slate-50 px-4 py-3 text-sm font-medium focus:bg-white" placeholder="Contoh: Stock data masih bisa minus" required>
                    </div>

                    <div class="md:col-span-2">
                        <label for="reference_url" class="mb-2 block text-sm font-black uppercase tracking-wide text-slate-800">Referensi</label>
                        <input id="reference_url" name="reference_url" type="text" maxlength="255" value="{{ old('reference_url', $issue?->reference_url) }}" class="w-full rounded-xl border-4 border-slate-900 bg-slate-50 px-4 py-3 text-sm font-medium focus:bg-white" placeholder="Contoh: Dokumentasi internal stok">
                    </div>

                    <div>
                        <label for="issue_date" class="mb-2 block text-sm font-black uppercase tracking-wide text-slate-800">Tanggal Issue</label>
                        <input id="issue_date" name="issue_date" type="date" value="{{ old('issue_date', $issue?->issue_date?->format('Y-m-d')) }}" class="w-full rounded-xl border-4 border-slate-900 bg-slate-50 px-4 py-3 text-sm font-medium focus:bg-white" required>
                    </div>

                    <div>
                        <label for="image" class="mb-2 block text-sm font-black uppercase tracking-wide text-slate-800">Screenshot / Gambar</label>
                        <input id="image" name="image" type="file" accept="image/*" class="w-full rounded-xl border-4 border-slate-900 bg-slate-50 px-4 py-3 text-sm font-medium focus:bg-white">
                        <p class="mt-2 text-xs font-medium text-slate-600">Tempel gambar dengan Ctrl+V atau pilih file. Maksimal 2 MB.</p>
                        <div id="imagePreview" class="mt-3 flex items-center gap-3" hidden aria-live="polite">
                            <img id="imagePreviewImage" alt="Pratinjau gambar yang dipilih" class="h-16 w-16 rounded-lg border-2 border-slate-900 object-cover">
                            <p id="imagePreviewName" class="break-all text-sm font-bold text-slate-700"></p>
                        </div>
                    </div>
                </div>

                @if($issue && $issue->image_path)
                    <div class="rounded-2xl border-4 border-slate-900 bg-slate-100 p-4">
                        <p class="mb-3 text-sm font-black uppercase tracking-wide text-slate-700">Gambar saat ini</p>
                        <img src="{{ route('issue.image', ['path' => $issue->image_path]) }}" alt="Issue current" class="h-52 rounded-xl border-4 border-slate-900 object-cover">
                        <label class="mt-4 flex items-center gap-2 text-sm font-bold text-slate-700">
                            <input type="checkbox" name="remove_image" value="1">
                            Hapus gambar saat ini
                        </label>
                    </div>
                @endif

            </form>

            <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                @if ($issue)
                    <form action="{{ route('issues.destroy', $issue) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus issue ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-xl border-4 border-slate-900 bg-rose-300 px-5 py-3 text-sm font-black shadow-[3px_3px_0_#111827]">
                            Hapus Issue
                        </button>
                    </form>
                @endif

                <div class="ml-auto flex justify-end gap-3">
                    <button type="reset" form="issueForm" class="rounded-xl border-4 border-slate-900 bg-slate-200 px-5 py-3 text-sm font-black shadow-[3px_3px_0_#111827]">
                        Reset
                    </button>
                    <button type="submit" form="issueForm" class="rounded-xl border-4 border-slate-900 bg-lime-300 px-5 py-3 text-sm font-black shadow-[3px_3px_0_#111827]">
                        {{ $issue ? 'Update Issue' : 'Simpan Issue' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            const issueForm = document.getElementById('issueForm');
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewImage = document.getElementById('imagePreviewImage');
            const imagePreviewName = document.getElementById('imagePreviewName');
            const removeImageCheckbox = issueForm.querySelector('[name="remove_image"]');
            let imagePreviewUrl;

            const showImagePreview = (file) => {
                if (imagePreviewUrl) {
                    URL.revokeObjectURL(imagePreviewUrl);
                }

                imagePreviewUrl = URL.createObjectURL(file);
                imagePreviewImage.src = imagePreviewUrl;
                imagePreviewName.textContent = `${file.name || 'Gambar dari clipboard'} (${Math.ceil(file.size / 1024)} KB)`;
                imagePreview.hidden = false;

                if (removeImageCheckbox) {
                    removeImageCheckbox.checked = false;
                }
            };

            const clearImagePreview = () => {
                if (imagePreviewUrl) {
                    URL.revokeObjectURL(imagePreviewUrl);
                    imagePreviewUrl = null;
                }

                imagePreviewImage.removeAttribute('src');
                imagePreviewName.textContent = '';
                imagePreview.hidden = true;
            };

            imageInput.addEventListener('change', () => {
                const file = imageInput.files[0];

                if (file) {
                    showImagePreview(file);
                } else {
                    clearImagePreview();
                }
            });

            issueForm.addEventListener('paste', (event) => {
                const imageItem = Array.from(event.clipboardData?.items ?? [])
                    .find((item) => item.type.startsWith('image/'));
                const file = imageItem?.getAsFile();

                if (!file) {
                    return;
                }

                event.preventDefault();

                const transfer = new DataTransfer();
                transfer.items.add(file);
                imageInput.files = transfer.files;
                imageInput.dispatchEvent(new Event('change', { bubbles: true }));
            });

            issueForm.addEventListener('reset', () => {
                window.setTimeout(clearImagePreview, 0);
            });
        </script>
    @endpush
@endsection
