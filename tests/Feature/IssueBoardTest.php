<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Issue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IssueBoardTest extends TestCase
{
    use RefreshDatabase;

    public function test_issue_list_can_be_filtered_by_title_and_status(): void
    {
        $department = Department::create(['name' => 'Operasional']);

        Issue::create([
            'department_id' => $department->id,
            'title' => 'Stock data masih bisa minus',
            'reference_url' => 'https://example.com/stock',
            'issue_date' => '2026-09-15',
            'status' => 'progress',
        ]);

        Issue::create([
            'department_id' => $department->id,
            'title' => 'Laporan absensi belum sinkron',
            'reference_url' => 'https://example.com/attendance',
            'issue_date' => '2026-09-20',
            'status' => 'todo',
        ]);

        $response = $this->get('/?search=stock&status=progress');

        $response->assertOk();
        $response->assertSee('Stock data masih bisa minus');
        $response->assertDontSee('Laporan absensi belum sinkron');
    }

    public function test_reference_accepts_plain_text_and_is_not_rendered_as_a_link(): void
    {
        $department = Department::create(['name' => 'Operasional']);
        $reference = 'Dokumentasi internal stok';

        $this->post(route('issues.store'), [
            'department_id' => $department->id,
            'title' => 'Pemeriksaan stok',
            'reference_url' => $reference,
            'issue_date' => '2026-09-15',
            'status' => 'todo',
        ])->assertRedirect(route('issues.index'));

        $issue = Issue::where('title', 'Pemeriksaan stok')->firstOrFail();

        $this->assertSame($reference, $issue->reference_url);
        $this->get(route('issues.index'))
            ->assertSee($reference)
            ->assertDontSee('href="'.$reference.'"', false);
        $this->get(route('issues.show', $issue))
            ->assertSee($reference)
            ->assertDontSee('href="'.$reference.'"', false);
    }

    public function test_delete_button_is_available_on_edit_form_only(): void
    {
        $department = Department::create(['name' => 'Operasional']);
        $issue = Issue::create([
            'department_id' => $department->id,
            'title' => 'Pemeriksaan stok',
            'issue_date' => '2026-09-15',
            'status' => 'todo',
        ]);

        $this->get(route('issues.edit', $issue))
            ->assertSee('Hapus Issue')
            ->assertSee('name="_method" value="DELETE"', false);
        $this->get(route('issues.create'))->assertDontSee('Hapus Issue');
    }

    public function test_issue_can_be_deleted_from_the_edit_action(): void
    {
        $department = Department::create(['name' => 'Operasional']);
        $issue = Issue::create([
            'department_id' => $department->id,
            'title' => 'Pemeriksaan stok',
            'issue_date' => '2026-09-15',
            'status' => 'todo',
        ]);

        $response = $this->delete(route('issues.destroy', $issue));

        $response->assertRedirect(route('issues.index'));
        $this->assertDatabaseMissing('issues', ['id' => $issue->id]);
    }
}
