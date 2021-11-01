<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    private $taskModel, 
        $attendanceModel, 
        $attendanceTaskModel, 
        $attendancePauseModel;
    /**
     * Instantiate a new AttendanceController instance.
     * 
     */
    public function __construct()
    {
        $this->taskModel = new \App\Models\Task();
        $this->attendanceModel = new \App\Models\Attendance();
        $this->attendanceTaskModel = new \App\Models\AttendanceTask();
        $this->attendancePauseModel = new \App\Models\AttendancePause();
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
            $location = null;
            if($request->has('location') && $request->location != ''){
                $location = \App\Models\UserPreference::where('user_id', \Auth::user()->id)
                    ->where('uuid', $request->location)
                    ->firstOrFail()
                    ->id;
            }

            $attendance = $this->attendanceModel;
            $attendance->user_id = \Auth::user()->id;
            $attendance->location = $location;
            $attendance->date = date("Y-m-d", strtotime($request->date));
            $attendance->checkin_time = $request->time.':00';
            // $attendance->notes = $request;
            $attendance->save();

            // Task
            $task = [];
            foreach($request->task as $key => $taskRequest){
                if(($taskRequest['name'] != "" && $taskRequest['name'] != null) && ($taskRequest['progress'] != "" && $taskRequest['progress'] != null)){
                    // Create new Task
                    $taskData = $this->taskModel->create([
                        'user_id' => \Auth::user()->id,
                        'progress' => $taskRequest['progress'],
                        'name' => $taskRequest['name'],
                        'notes' => null
                    ]);

                    $task[] = new $this->attendanceTaskModel([
                        'task_id' => $taskData->id,
                        'progress_start' => 0,
                        'progress_end' => $taskData->progress
                    ]);
                }
            }

            if($request->has('validate') && $request->validate != ''){
                $uuid = explode(',', $request->validate);
                $taskDatas = $this->taskModel->where('user_id', \Auth::user()->id)
                    ->whereIn('uuid', $uuid)
                    ->get();
                if(count($taskDatas) > 0){
                    foreach($taskDatas as $taskData){
                        $task[] = new $this->attendanceTaskModel([
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
        $data = $this->attendanceModel
            ->with('attendanceTask', 'attendanceTask.task', 'location')
            ->where('uuid', $id)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched',
            'data' => $data
        ]);
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
        if($request->has('type') && $request->type != ''){
            if($request->type == 'pause'){
                return $this->updatePause($request, $id);
            } else if($request->type == 'new_task'){
                return $this->updateTask($request, $id);
            }
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
            $attendance = $this->attendanceModel->where('uuid', $id)->firstOrFail();
            $attendance->checkout_time = $request->time.':00';
            $attendance->save();

            // Task
            $task = [];
            foreach($request->task as $key => $taskRequest){
                if(isset($taskRequest['validate']) && !empty($taskRequest['validate'])){
                    // Get existing Task
                    $taskData = $this->attendanceTaskModel->where('uuid', $taskRequest['validate'])->firstOrFail();
                    $taskData->progress_end = $taskRequest['progress'];
                    $taskData->save();
                } else {
                    // Create new Task
                    $taskData = $this->taskModel->create([
                        'user_id' => \Auth::user()->id,
                        'progress' => $taskRequest['progress'],
                        'name' => $taskRequest['name'],
                        'notes' => null
                    ]);

                    $task[] = new $this->attendanceTaskModel([
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
            $attendance = $this->attendanceModel->where('uuid', $id)->firstOrFail();
            
            $pauseData = $this->attendancePauseModel;
            if($request->has('pause_id') && $request->pause_id != ''){
                $pauseData = $this->attendancePauseModel->where('id', $request->pause_id)
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateTask(Request $request, $id)
    {
        $request->validate([
            'task.*.include' => ['required', 'string'],
            'task.*.progress' => ['required', 'numeric', 'digits_between:0,100'],
            'task.*.name' => ['required', 'string', 'max:191']
        ], [
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
            $attendance = $this->attendanceModel->where('uuid', $id)->firstOrFail();
            
            // Task
            $task = [];
            foreach($request->task as $key => $taskRequest){
                if(($taskRequest['name'] != "" && $taskRequest['name'] != null) && ($taskRequest['progress'] != "" && $taskRequest['progress'] != null)){
                    // Create new Task
                    $taskData = $this->taskModel->create([
                        'user_id' => \Auth::user()->id,
                        'progress' => $taskRequest['progress'],
                        'name' => $taskRequest['name'],
                        'notes' => null
                    ]);

                    $task[] = new $this->attendanceTaskModel([
                        'task_id' => $taskData->id,
                        'progress_start' => 0,
                        'progress_end' => $taskData->progress
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
        $model = $this->attendanceModel;

        $data = $model->query();
        $data->select($model->getTable().'.*');

        if($request->has('filter_year') && $request->filter_year != ""){
            $data->whereYear('date', $request->filter_year);
        }
        if($request->has('filter_month') && $request->filter_month != ""){
            $data->whereMonth('date', $request->filter_month);
        }

        return datatables()
            ->of($data->orderBy('date', 'desc')->withCount('attendanceTask', 'attendancePause')->with('user', 'attendanceTask', 'attendanceTask.task', 'location'))
            ->toJson();
    }
}
