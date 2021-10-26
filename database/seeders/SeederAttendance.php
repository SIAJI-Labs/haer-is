<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendancePause;
use App\Models\AttendanceTask;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeederAttendance extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        AttendancePause::truncate();
        AttendanceTask::truncate();
        Attendance::truncate();
        Schema::enableForeignKeyConstraints();
    }
}
