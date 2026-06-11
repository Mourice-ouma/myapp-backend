<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Youth',             'description' => 'Youth ministry for young members'],
            ['name' => 'Choir',             'description' => 'Church choir and music ministry'],
            ['name' => 'Deacons',           'description' => 'Deacons ministry'],
            ['name' => 'Deaconesses',       'description' => 'Deaconesses ministry'],
            ['name' => 'Prayer Ministry',   'description' => 'Intercessory prayer and prayer warriors'],
            ['name' => 'Sabbath School',    'description' => 'Sabbath school classes and leadership'],
            ['name' => 'Children Ministry', 'description' => 'Ministry for children and young families'],
            ['name' => 'Health Ministry',   'description' => 'Church health and wellness ministry'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept['name']], $dept);
        }
    }
}
