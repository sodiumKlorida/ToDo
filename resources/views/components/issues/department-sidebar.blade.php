@props(['departments'])

<aside class="rounded-[28px] border-4 border-slate-900 bg-white p-4 shadow-[8px_8px_0_#111827]">
    {{-- <div class="mb-4">
        <p class="text-xs font-black uppercase tracking-[0.25em] text-slate-500">Navigation</p>
    </div> --}}

    <nav class="space-y-3">
        <a href="{{ route('issues.index') }}" class="block rounded-xl border-4 {{ request('department_id') ? 'border-slate-900 bg-slate-100' : 'border-slate-900 bg-yellow-300' }} px-4 py-3 text-sm font-black shadow-[3px_3px_0_#111827]">
            Semua Departemen
        </a>

        @foreach ($departments as $department)
            @php
                $isActive = (string) request('department_id') === (string) $department->id;
            @endphp
            <a href="{{ route('issues.index', ['department_id' => $department->id]) }}" class="block rounded-xl border-4 {{ $isActive ? 'border-slate-900 bg-sky-300' : 'border-slate-900 bg-slate-100' }} px-4 py-3 text-sm font-black shadow-[3px_3px_0_#111827]">
                {{ $department->name }}
            </a>
        @endforeach
    </nav>
</aside>
