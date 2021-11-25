<?php

namespace App\Http\Controllers\System;

use Auth;
use Hash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private $userModel;

    /**
     * Instantiate a new StaffController instance.
     * 
     */
    public function __construct()
    {
        $this->userModel = new \App\Models\User();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('content.system.profile.index');
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
        $data = $this->userModel->where('uuid', $id)
            ->firstOrFail();

        $passwordValidation = $oldPasswordValidation = ['nullable', 'string'];
        if($request->has('password') && !empty($request->password) && $request->password != ''){
            $passwordValidation = ['nullable', 'string', 'min:5', 'confirmed'];
            $oldPasswordValidation = ['required', 'string'];
        }

        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'username' => ['required', 'string', 'max:10', 'unique:'.$this->userModel->getTable().',username,'.$data->id],
            'email' => ['required', 'email', 'unique:'.$this->userModel->getTable().',email,'.$data->id],
            'password' => $passwordValidation,
            'old_password' => $oldPasswordValidation
        ], [
            'store_id.string' => 'Nilai pada Field Toko tidak valid!',
            'store_id.exists' => 'Nilai pada Field Toko tidak tersedia!',
            'name.required' => 'Field Nama wajib diisi!',
            'name.string' => 'Nilai pada Field Nama tidak valid!',
            'name.max' => 'Nilai pada Field Nama melebihi batas jumlah karakter (191)!',
            'username.required' => 'Field Username wajib diisi!',
            'username.string' => 'Nilai pada Field Username tidak valid!',
            'username.max' => 'Nilai pada Field Username melebihi batas jumlah karakter (10)!',
            'username.unique' => 'Nilai pada Field Username sudah digunakan!',
            'email.required' => 'Field Email wajib diisi!',
            'email.email' => 'Nilai pada Field Email tidak valid!',
            'email.unique' => 'Nilai pada Field Email sudah digunakan!',
            'password.required' => 'Field Password wajib diisi!',
            'password.string' => 'Nilai pada Field Password tidak valid!',
            'password.min' => 'Nilai pada Field Password belum memenuhi panjang minimal karakter (Minimal: 5 karakter)!',
            'password.confirmed' => 'Nilai pada Field Konfirmasi Password dan Nilai pada Field Password berbeda!',
            'old_password.required' => 'Field Password Lama wajib diisi!',
            'old_password.string' => 'Nilai pada Field Password Lama tidak valid!'
        ]);

        if($request->has('password') && !empty($request->password)){
            if(Hash::check($request->password, $data->password)){
                return redirect()->back()->withInput()->withErrors([
                    'password' => ['Tidak dapat menggunakan data terkini sebagai Nilai pada Field Password!']
                ]);
            } else if(!(Hash::check($request->old_password, $data->password))){
                return redirect()->back()->withInput()->withErrors([
                    'old_password' => ['Nilai pada Password Lama tidak cocok dengan data terkini!']
                ]);
            } else {
                $data->password = Hash::make($request->password);
            }
        }

        $data->name = $request->name;
        $data->email = $request->email;
        $data->username = $request->username;
        $data->avatar_style = $request->avatar_style;
        $data->save();
        
        return redirect()->route('system.profile.index')->with([
            'status' => 'success',
            'message' => 'Berhasil memperbaharui data Profile'
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
}
