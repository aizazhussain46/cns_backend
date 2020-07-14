<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('supporter_name')->before('created_at')->nullable();
            $table->string('supporter_type')->before('created_at')->nullable();
            $table->string('supporter_contact')->before('created_at')->nullable();
            $table->string('referred_by')->before('created_at')->nullable();
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
            $table->dropColumn('supporter_name');
            $table->dropColumn('supporter_type');
            $table->dropColumn('supporter_contact');
            $table->dropColumn('referred_by');
        });
    }
}
