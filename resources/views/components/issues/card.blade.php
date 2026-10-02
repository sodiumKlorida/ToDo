@props(['issue'])

@php
    $statusColors = [
        'todo' => 'bg-amber-200 text-amber-900 border-amber-900',
        'progress' => 'bg-sky-200 text-sky-900 border-sky-900',
        'done' => 'bg-emerald-200 text-emerald-900 border-emerald-900',
    ];
    $statusClass = $statusColors[$issue->status] ?? 'bg-slate-200 text-slate-800 border-slate-900';
@endphp

<article class="flex h-full flex-col rounded-[28px] border-4 border-slate-900 bg-[#fffdf9] p-4 shadow-[8px_8px_0_#111827]">
    @if ($issue->image_path)
        <button type="button" class="mb-4 block overflow-hidden rounded-2xl border-4 border-slate-900 bg-white shadow-[4px_4px_0_#111827]" data-image-modal-trigger="{{ route('issue.image', ['path' => $issue->image_path]) }}" data-image-title="{{ $issue->title }}">
            <img src="{{ route('issue.image', ['path' => $issue->image_path]) }}" alt="Issue screenshot" class="h-44 w-full object-cover transition hover:scale-105">
        </button>
    @else
        <div class="mb-4 flex h-44 items-center justify-center rounded-2xl border-4 border-dashed border-slate-300 bg-slate-100 text-sm font-black uppercase tracking-wide text-slate-500">
            No image
        </div>
    @endif

    <div class="mb-3 flex items-center justify-between gap-3">
        <span class="rounded-full border-2 border-slate-900 bg-white px-2 py-1 text-[10px] font-black uppercase tracking-wide text-slate-700">
            {{ $issue->department->name ?? 'Unassigned' }}
        </span>
        <span class="rounded-full border-2 border-slate-900 px-2 py-1 text-[10px] font-black uppercase tracking-wide {{ $statusClass }}">
            {{ $issue->status }}
        </span>
    </div>

    <h2 class="text-xl font-black leading-tight">{{ $issue->title }}</h2>
    <p class="mt-3 text-sm font-medium text-slate-600">{{ $issue->issue_date->format('d M Y') }}</p>

    @if ($issue->reference_url)
        <p class="mt-4 text-sm font-medium text-slate-700">
            {{ $issue->reference_url }}
        </p>
    @endif

    <div class="mt-5 pt-4">
        <a href="{{ route('issues.edit', $issue) }}" class="block w-full rounded-xl border-4 border-slate-900 bg-orange-300 px-3 py-2 text-center text-sm font-black shadow-[3px_3px_0_#111827]">
            Ubah
        </a>
    </div>
</article>
