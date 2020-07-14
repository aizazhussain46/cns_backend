<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id')->nullable();
            $table->string('contact_of_ptb_case')->nullable();
			$table->string('diabetes')->nullable();
			$table->string('malnutrition')->nullable();
            $table->string('hiv_inf_aids')->nullable();
            $table->string('smoking')->nullable();
			$table->string('hcw')->nullable();
            $table->string('risk_f_other')->nullable();
            $table->string('pulmonary')->nullable();
			$table->string('e_pulmonary')->nullable();
			$table->string('pleura')->nullable();
            $table->string('lymph_node')->nullable();
            $table->string('abdomen')->nullable();
			$table->string('bones')->nullable();
            $table->string('d_site_other')->nullable();
            $table->string('histology')->nullable();
            $table->string('x_ray')->nullable();
            $table->string('u_sound')->nullable();
			$table->string('mri')->nullable();
			$table->string('eptb_other')->nullable();
            $table->string('tb_patient_type')->nullable();
            $table->string('bacteriology_confirmed')->nullable();
            $table->string('clinically_diagnosed')->nullable();
            $table->string('retreatment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diagnoses');
    }
}
