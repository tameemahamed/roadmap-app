<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $status_names = ['Planned', 'In Progress', 'Completed'];
        foreach ($status_names as $status_name) {
            Status::insert([
                'status_name' => $status_name
            ]);
        }
    }
}
