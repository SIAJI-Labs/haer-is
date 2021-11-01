<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilePreferenceController extends Controller
{
    private $userPreference;
    /**
     * Instantiate a new ProfilePreference instance.
     * 
     */
    public function __construct()
    {
        $this->userPreference = new \App\Models\UserPreference();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->userPreference->where('user_id', \Auth::user()->id);
        if($request->has('type') && $request->type != ''){
            $data->where('key', $request->type);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched',
            'data' => $data->get()
        ]);
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
        //
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
        if($request->has('type') && $request->type != ''){
            if($request->type == 'location'){
                $request->validate([
                    'location.*.is_included' => ['nullable', 'string'],
                    'location.*.name' => ['required', 'string', 'distinct']
                ], [
                    'location.*.is_included.string' => 'Nilai dari Field Default tidak valid!',
                    'location.*.name.required' => 'Field Lokasi wajib diisi!',
                    'location.*.name.string' => 'Nilai dari Field Lokasi tidak valid!',
                    'location.*.name.distinct' => 'Terdapat duplikat pada Nilai dari Field Lokasi!',
                ]);

                \DB::transaction(function () use ($request) {
                    foreach($request->location as $key => $location){
                        if($request->has('location.'.$key.'.validate') && $request->location[$key]['validate'] != ''){
                            $updateData = $this->userPreference
                                ->where('user_id', \Auth::user()->id)
                                ->where('uuid', $request->location[$key]['validate'])
                                ->firstOrFail();

                            $updateData->key = 'location';
                            $updateData->value = $request->location[$key]['name'];
                            $updateData->is_default = $request->has('location.'.$key.'.is_default') ? true : false;
                            $updateData->save();
                        } else {
                            $newData = new $this->userPreference;
                            $newData->user_id = \Auth::user()->id;
                            $newData->key = 'location';
                            $newData->value = $request->location[$key]['name'];
                            $newData->is_default = $request->has('location.'.$key.'.is_default') ? true : false;
                            $newData->save();
                        }
                    }
                });

                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil diperbaharui!',
                    'data' => [
                        'location_default' => \Auth::user()->userPreference()->where('key', 'location')->where('is_default', true)->first()
                    ]
                ]);
            }
        }
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
     * JSON for Select2
     */
    public function select2(Request $request)
    {
        $data = $this->userPreference->query();
        $last_page = null;

        $data->where('user_id', \Auth::user()->id);
        if($request->has('type') && $request->type != ''){
            $data->where('key', $request->type);
        }

        if($request->has('search') && $request->search != ''){
            // Apply search param
            $data = $data->where('value', 'like', '%'.$request->search.'%');
        }

        if($request->has('page')){
            // If request has page parameter, add paginate to eloquent
            $data->paginate(10);
            // Get last page
            $last_page = $data->paginate(10)->lastPage();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data Fetched',
            'last_page' => $last_page,
            'data' => $data->get(),
        ]);
    }
}
