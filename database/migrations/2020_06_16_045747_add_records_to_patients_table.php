<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecordsToPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('father_name')->before('created_at')->nullable();
            $table->string('gender')->before('created_at')->nullable();
            $table->string('age')->before('created_at')->nullable();
            $table->string('occupation')->before('created_at')->nullable();
            $table->string('dob')->before('created_at')->nullable();
            $table->string('reg_date')->before('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('father_name');
            $table->dropColumn('gender');
            $table->dropColumn('age');
            $table->dropColumn('occupation');
            $table->dropColumn('dob');
            $table->dropColumn('reg_date');
        });
    }
}
