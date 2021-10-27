<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Instantiate a new AttendanceController instance.
     * 
     */
    public function __construct()
    {
        // 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateProgress = ['required', 'numeric', 'digits_between:0,100'];
        $validateName = ['required', 'string', 'max:191'];
        if($request->has('validate') && $request->validate != ''){
            $validateProgress = ['nullable', 'numeric', 'digits_between:0,100'];
            $validateName = ['nullable', 'string', 'max:191'];
        }
        $request->validate([
            'date' => ['required', 'string'],
            'time' => ['required', 'string'],
            'task.*.include' => ['required', 'string'],
            'task.*.progress' => $validateProgress,
            'task.*.name' => $validateName
        ], [
            'date.required' => 'Field Tanggal Kehadiran wajib diisi!',
            'date.string' => 'Nilai pada Field Tanggal Kehadiran tidak valid!',
            'time.required' => 'Field Jam Kehadiran wajib diisi!',
            'time.string' => 'Nilai pada Field Jam Kehadiran tidak valid!',
            'task.*.include.required' => 'Field [checklist] wajib diisi!',
            'task.*.include.string' => 'Nilai pada Field [checklist] tidak valid!',
            'task.*.progress.required' => 'Field Progress wajib diisi!',
            'task.*.progress.numeric' => 'Nilai pada Field Progress tidak valid!',
            'task.*.progress.digits_between' => 'Nilai pada Field Progress harus diantara 0 - 100',
            'task.*.name.required' => 'Field Nama wajib diisi!',
            'task.*.name.string' => 'Nilai pada Field Nama tidak valid!',
            'task.*.name.max' => 'Nilai pada Field Nama maksimal 191 karakter!',
        ]);

        \DB::transaction(function () use ($request) {
            $attendance = new \App\Models\Attendance();
            $attendance->user_id = \Auth::user()->id;
            $attendance->date = date("Y-m-d", strtotime($request->date));
            $attendance->checkin_time = $request->time.':00';
            // $attendance->notes = $request;
            $attendance->save();

            // Task
            $task = [];
            foreach($request->task as $key => $taskRequest){
                if(($taskRequest['name'] != "" && $taskRequest['name'] != null) && ($taskRequest['progress'] != "" && $taskRequest['progress'] != null)){
                    // Create new Task
                    $taskData = \App\Models\Task::create([
                        'user_id' => \Auth::user()->id,
                        'progress' => $taskRequest['progress'],
                        'name' => $taskRequest['name'],
                        'notes' => null
                    ]);

                    $task[] = new \App\Models\AttendanceTask([
                        'task_id' => $taskData->id,
                        'progress_start' => 0,
                        'progress_end' => $taskData->progress
                    ]);
                }
            }

            if($request->has('validate') && $request->validate != ''){
                $uuid = explode(',', $request->validate);
                $taskDatas = \App\Models\Task::where('user_id', \Auth::user()->id)
                    ->whereIn('uuid', $uuid)
                    ->get();
                if(count($taskDatas) > 0){
                    foreach($taskDatas as $taskData){
                        $task[] = new \App\Models\AttendanceTask([
                            'task_id' => $taskData->id,
                            'progress_start' => $taskData->progress,
                            'progress_end' => $taskData->progress
                        ]);
                    }
                }
            }

            // Apply Task to Attendance Data
            $attendance->attendanceTask()->saveMany($task);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan!',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->has('type') && $request->type == 'pause'){
            return $this->updatePause($request, $id);
        }

        $request->validate([
            'date' => ['required', 'string'],
            'time' => ['required', 'string'],
            'task.*.include' => ['required', 'string'],
            'task.*.progress' => ['required', 'numeric', 'digits_between:0,100'],
            'task.*.name' => ['required', 'string', 'max:191']
        ], [
            'date.required' => 'Field Tanggal Kehadiran wajib diisi!',
            'date.string' => 'Nilai pada Field Tanggal Kehadiran tidak valid!',
            'time.required' => 'Field Jam Kehadiran wajib diisi!',
            'time.string' => 'Nilai pada Field Jam Kehadiran tidak valid!',
            'task.*.include.required' => 'Field [checklist] wajib diisi!',
            'task.*.include.string' => 'Nilai pada Field [checklist] tidak valid!',
            'task.*.progress.required' => 'Field Progress wajib diisi!',
            'task.*.progress.numeric' => 'Nilai pada Field Progress tidak valid!',
            'task.*.progress.digits_between' => 'Nilai pada Field Progress harus diantara 0 - 100',
            'task.*.name.required' => 'Field Nama wajib diisi!',
            'task.*.name.string' => 'Nilai pada Field Nama tidak valid!',
            'task.*.name.max' => 'Nilai pada Field Nama maksimal 191 karakter!',
        ]);

        \DB::transaction(function () use ($request, $id) {
            $attendance = \App\Models\Attendance::where('uuid', $id)->firstOrFail();
            $attendance->checkout_time = $request->time.':00';
            $attendance->save();

            // Task
            $task = [];
            foreach($request->task as $key => $taskRequest){
                if(isset($taskRequest['validate']) && !empty($taskRequest['validate'])){
                    // Get existing Task
                    $taskData = \App\Models\AttendanceTask::findOrFail($taskRequest['validate']);
                    $taskData->progress_end = $taskRequest['progress'];
                    $taskData->save();
                } else {
                    // Create new Task
                    $taskData = \App\Models\Task::create([
                        'user_id' => \Auth::user()->id,
                        'progress' => $taskRequest['progress'],
                        'name' => $taskRequest['name'],
                        'notes' => null
                    ]);

                    $task[] = new \App\Models\AttendanceTask([
                        'task_id' => $taskData->id,
                        'progress_start' => 0,
                        'progress_end' => $taskRequest['progress']
                    ]);
                }
            }

            if(!empty($task)){
                // Apply Task to Attendance Data
                $attendance->attendanceTask()->saveMany($task);
            }
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbaharui!',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePause(Request $request, $id)
    {
        $request->validate([
            'time' => ['required', 'string'],
            'reason' => ['required', 'string', 'max:191']
        ], [
            'time.required' => 'Field Jam wajib diisi!',
            'dattimee.string' => 'Nilai pada Field Jam tidak valid!',
            'reason.required' => 'Field Alasan wajib diisi!',
            'reason.string' => 'Nilai pada Field Alasan tidak valid!',
            'reason.max' => 'Nilai pada Field Alasan maksimal 191 karakter!',
        ]);

        \DB::transaction(function () use ($request, $id) {
            $attendance = \App\Models\Attendance::where('uuid', $id)->firstOrFail();
            
            $pauseData = new \App\Models\AttendancePause();
            if($request->has('pause_id') && $request->pause_id != ''){
                $pauseData = \App\Models\AttendancePause::where('id', $request->pause_id)
                    ->where('attendance_id', $attendance->id)
                    ->firstOrFail();

                $pauseData->end = $request->time.':00';

                // Get Duration
                $start = date_create($pauseData->start);
                $end = date_create($pauseData->end);
                $differenceInHours = date_diff($end, $start);

                $differenceInMinutes = $differenceInHours->m * 60;
                $differenceInMinutes += $differenceInHours->i;

                $pauseData->duration = $differenceInMinutes;
            } else {
                $pauseData->attendance_id = $attendance->id;
                $pauseData->start = $request->time.':00';
            }

            $pauseData->notes = $request->reason;
            $pauseData->save();
        });
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbaharui'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Task List (Datatable)
     * 
     */
    public function datatableAll(Request $request)
    {
        $model = new \App\Models\Attendance();

        $data = $model->query();
        $data->select($model->getTable().'.*');

        return datatables()
            ->of($data->orderBy('date', 'desc')->withCount('attendanceTask', 'attendancePause')->with('user', 'attendanceTask', 'attendanceTask.task'))
            ->toJson();
    }
}
