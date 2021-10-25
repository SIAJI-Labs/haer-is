<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeederUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        User::create([
            'name' => 'User',
            'username' => 'user',
            'email' => 'user@mail.com',
            'password' => bcrypt('user'),
            'is_active' => true
        ]);

        User::create([
            'name' => 'Dwi Aji',
            'username' => 'dwiaji',
            'email' => 'si.aji03@gmail.com',
            'password' => bcrypt('dwiaji'),
            'is_active' => true
        ]);
    }
}
