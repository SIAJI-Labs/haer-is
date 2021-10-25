<?php

namespace App\Observers;

use App\Models\AttendanceTask;

class AttendanceTaskObserver
{
    /**
     * Handle the AttendanceTask "created" event.
     *
     * @param  \App\Models\AttendanceTask  $attendanceTask
     * @return void
     */
    public function created(AttendanceTask $attendanceTask)
    {
        //
    }

    /**
     * Handle the AttendanceTask "updated" event.
     *
     * @param  \App\Models\AttendanceTask  $attendanceTask
     * @return void
     */
    public function updated(AttendanceTask $attendanceTask)
    {
        $currValue = $attendanceTask->progress_end;

        $updateTask = \App\Models\Task::findOrFail($attendanceTask->task_id);
        $updateTask->progress = $currValue;
        $updateTask->save();
    }

    /**
     * Handle the AttendanceTask "deleted" event.
     *
     * @param  \App\Models\AttendanceTask  $attendanceTask
     * @return void
     */
    public function deleted(AttendanceTask $attendanceTask)
    {
        //
    }

    /**
     * Handle the AttendanceTask "restored" event.
     *
     * @param  \App\Models\AttendanceTask  $attendanceTask
     * @return void
     */
    public function restored(AttendanceTask $attendanceTask)
    {
        //
    }

    /**
     * Handle the AttendanceTask "force deleted" event.
     *
     * @param  \App\Models\AttendanceTask  $attendanceTask
     * @return void
     */
    public function forceDeleted(AttendanceTask $attendanceTask)
    {
        //
    }
}
