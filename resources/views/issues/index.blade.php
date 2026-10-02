@extends('layouts.app')

@section('title', 'Issue Board Operasional')

@section('content')
    <div class="mx-auto w-full px-4 py-8 md:px-8">
        {{-- <x-issues.page-header /> --}}

        <div class="grid gap-6 lg:grid-cols-[260px_minmax(0,1fr)]">
            <x-issues.department-sidebar :departments="$departments" />

            <div>
                @if (session('success'))
                    <div
                        class="mb-6 rounded-2xl border-4 border-slate-900 bg-emerald-200 px-4 py-3 text-sm font-bold shadow-[4px_4px_0_#111827]">
                        {{ session('success') }}
                    </div>
                @endif

                <section class="mb-8 rounded-[28px] border-4 border-slate-900 bg-white p-5 shadow-[8px_8px_0_#111827]">
                    <form action="{{ route('issues.index') }}" method="GET" class="grid gap-4 md:grid-cols-4">
                        @if (request('department_id'))
                            <input type="hidden" name="department_id" value="{{ request('department_id') }}">
                        @endif

                        <div class="md:col-span-2">
                            <label for="search"
                                class="mb-2 block text-sm font-bold uppercase tracking-wide text-slate-700">Cari
                                judul</label>
                            <input id="search" name="search" value="{{ request('search') }}" type="text"
                                placeholder="Cari issue..."
                                class="w-full rounded-xl border-4 border-slate-900 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:bg-white">
                        </div>
                        <div>
                            <label for="issue_date"
                                class="mb-2 block text-sm font-bold uppercase tracking-wide text-slate-700">Tanggal</label>
                            <input id="issue_date" name="issue_date" value="{{ request('issue_date') }}" type="date"
                                class="w-full rounded-xl border-4 border-slate-900 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:bg-white">
                        </div>
                        <div>
                            <label for="status"
                                class="mb-2 block text-sm font-bold uppercase tracking-wide text-slate-700">Status</label>
                            <select id="status" name="status"
                                class="w-full rounded-xl border-4 border-slate-900 bg-slate-50 px-4 py-3 text-sm font-medium outline-none focus:bg-white">
                                <option value="">Semua status</option>
                                <option value="todo" {{ request('status') === 'todo' ? 'selected' : '' }}>To Do</option>
                                <option value="progress" {{ request('status') === 'progress' ? 'selected' : '' }}>Progress
                                </option>
                                <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                        </div>
                        <div class="md:col-span-4 flex items-center gap-3">
                            <button type="submit"
                                class="rounded-xl border-4 border-slate-900 bg-sky-300 px-4 py-3 text-sm font-black shadow-[3px_3px_0_#111827] transition hover:translate-x-[-1px] hover:translate-y-[-1px]">Filter</button>
                            <a href="{{ route('issues.index') }}"
                                class="rounded-xl border-4 border-slate-900 bg-slate-200 px-4 py-3 text-sm font-black shadow-[3px_3px_0_#111827] transition">Reset</a>
                            <a href="{{ route('issues.create') }}"
                                class="inline-flex items-center justify-center rounded-xl border-4 border-slate-900 bg-yellow-300 px-5 py-3 text-sm font-black shadow-[4px_4px_0_#111827] transition hover:translate-x-[-2px] hover:translate-y-[-2px] hover:shadow-[6px_6px_0_#111827]">
                                + Tambah Issue
                            </a>
                        </div>
                    </form>
                </section>

                @if ($issues->isEmpty())
                    <div
                        class="rounded-[28px] border-4 border-dashed border-slate-400 bg-slate-100 p-10 text-center text-lg font-bold text-slate-600">
                        Tidak ada issue yang sesuai dengan filter saat ini.
                    </div>
                @else
                    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($issues as $issue)
                            <x-issues.card :issue="$issue" />
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-issues.image-modal />
@endsection
