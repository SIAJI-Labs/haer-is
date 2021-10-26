<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(\Database\Seeders\SeederUser::class);
        $this->call(\Database\Seeders\SeederAttendance::class);
        $this->call(\Database\Seeders\SeederTask::class);
    }
}
