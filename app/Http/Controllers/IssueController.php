<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Issue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Issue::with('department')
            ->orderByDesc('issue_date')
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.trim($request->search).'%');
        }

        if ($request->filled('issue_date')) {
            $query->whereDate('issue_date', $request->issue_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $issues = $query->get();
        $departments = Department::orderBy('name')->get();

        return view('issues.index', compact('issues', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('issues.form', [
            'issue' => null,
            'departments' => Department::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'title' => ['required', 'string', 'max:255'],
            'reference_url' => ['nullable', 'string', 'max:255'],
            'issue_date' => ['required', 'date'],
            'status' => ['required', 'in:todo,progress,done'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('issues', $this->imageDisk());
        }

        Issue::create($validated);

        return redirect()->route('issues.index')->with('success', 'Issue berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue): View
    {
        return view('issues.show', compact('issue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Issue $issue): View
    {
        return view('issues.form', [
            'issue' => $issue,
            'departments' => Department::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Issue $issue): RedirectResponse
    {
        $validated = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'title' => ['required', 'string', 'max:255'],
            'reference_url' => ['nullable', 'string', 'max:255'],
            'issue_date' => ['required', 'date'],
            'status' => ['required', 'in:todo,progress,done'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $disk = $this->imageDisk();

        if ($request->hasFile('image')) {
            if ($issue->image_path && Storage::disk($disk)->exists($issue->image_path)) {
                Storage::disk($disk)->delete($issue->image_path);
            }

            $validated['image_path'] = $request->file('image')->store('issues', $disk);
        }

        if ($request->boolean('remove_image')) {
            if ($issue->image_path && Storage::disk($disk)->exists($issue->image_path)) {
                Storage::disk($disk)->delete($issue->image_path);
            }

            $validated['image_path'] = null;
        }

        $issue->update($validated);

        return redirect()->route('issues.index')->with('success', 'Issue berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue): RedirectResponse
    {
        $disk = $this->imageDisk();

        if ($issue->image_path && Storage::disk($disk)->exists($issue->image_path)) {
            Storage::disk($disk)->delete($issue->image_path);
        }

        $issue->delete();

        return redirect()->route('issues.index')->with('success', 'Issue berhasil dihapus.');
    }

    protected function imageDisk(): string
    {
        return config('filesystems.default', 'public');
    }
}
