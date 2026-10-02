<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Issue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IssueSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departmentIds = Department::pluck('id')->all();

        if (empty($departmentIds)) {
            return;
        }

        $issues = [
            [
                'department_id' => $departmentIds[0],
                'title' => 'Stock data masih bisa minus',
                'reference_url' => 'https://domain.com/admin/stock',
                'issue_date' => '2026-09-02',
                'status' => 'progress',
            ],
            [
                'department_id' => $departmentIds[1],
                'title' => 'Invoice supplier belum otomatis tertutup',
                'reference_url' => 'https://domain.com/admin/invoices',
                'issue_date' => '2026-09-05',
                'status' => 'todo',
            ],
            [
                'department_id' => $departmentIds[2],
                'title' => 'Data onboarding karyawan belum masuk ke dashboard',
                'reference_url' => 'https://domain.com/admin/hr',
                'issue_date' => '2026-09-08',
                'status' => 'done',
            ],
        ];

        foreach ($issues as $issue) {
            Issue::firstOrCreate(
                ['title' => $issue['title']],
                $issue
            );
        }
    }
}
