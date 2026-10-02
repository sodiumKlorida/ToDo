@extends('layouts.app')

@section('title', $issue->title)

@section('content')
    <div class="p-8">
    <div class="mx-auto max-w-3xl rounded-[28px] border-4 border-slate-900 bg-white p-6 shadow-[8px_8px_0_#111827]">
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.3em] text-slate-500">Issue detail</p>
                <h1 class="mt-2 text-3xl font-black">{{ $issue->title }}</h1>
            </div>
            <a href="{{ route('issues.index') }}" class="rounded-xl border-4 border-slate-900 bg-slate-200 px-4 py-2 text-sm font-black">Kembali</a>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <p class="text-sm font-black uppercase tracking-wide text-slate-700">Departemen</p>
                <p class="mt-2 text-lg font-bold">{{ $issue->department->name }}</p>
            </div>
            <div>
                <p class="text-sm font-black uppercase tracking-wide text-slate-700">Status</p>
                <p class="mt-2 text-lg font-bold capitalize">{{ $issue->status }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm font-black uppercase tracking-wide text-slate-700">Tanggal</p>
                <p class="mt-2 text-lg font-bold">{{ $issue->issue_date->format('d M Y') }}</p>
            </div>
            @if($issue->reference_url)
                <div class="md:col-span-2">
                    <p class="text-sm font-black uppercase tracking-wide text-slate-700">Referensi</p>
                    <p class="mt-2 text-lg font-bold">{{ $issue->reference_url }}</p>
                </div>
            @endif
        </div>

        @if($issue->image_path)
            <div class="mt-8">
                <p class="mb-3 text-sm font-black uppercase tracking-wide text-slate-700">Screenshot</p>
                <img src="{{ route('issue.image', ['path' => $issue->image_path]) }}" alt="Issue screenshot" class="w-full rounded-2xl border-4 border-slate-900 object-cover">
            </div>
        @endif
    </div>
    </div>
@endsection
