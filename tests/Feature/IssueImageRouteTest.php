<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Issue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IssueImageRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_uploaded_issue_image_is_served_from_a_public_route(): void
    {
        $department = Department::create(['name' => 'Operasional']);

        $issue = Issue::create([
            'department_id' => $department->id,
            'title' => 'Stock data masih bisa minus',
            'reference_url' => 'https://example.com/stock',
            'issue_date' => '2026-09-15',
            'status' => 'progress',
        ]);

        $file = UploadedFile::fake()->create('issue.txt', 10);
        $path = 'issues/' . $file->hashName();
        Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));

        $issue->update(['image_path' => $path]);

        $response = $this->get(route('issue.image', ['path' => $path]));

        $response->assertOk();
    }
}
