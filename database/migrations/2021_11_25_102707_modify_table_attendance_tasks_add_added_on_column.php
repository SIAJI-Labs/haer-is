<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_tasks', function (Blueprint $table) {
            $table->enum('added_on', ['check-in', 'working', 'check-out'])->nullable()->after('progress_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_tasks', function (Blueprint $table) {
            $table->dropColumn('added_on');
        });
    }
};
