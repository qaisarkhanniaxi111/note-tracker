<?php

namespace Database\Seeders;

use App\Models\NoteStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NoteStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['not-fixed', 'fixed', 'contact me'];

        foreach($statuses as $status) {
            NoteStatus::create([
                'name' => $status
            ]);
        }
    }
}
