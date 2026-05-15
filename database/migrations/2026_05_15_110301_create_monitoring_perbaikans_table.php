<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitoringPerbaikansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitoring_perbaikans', function (Blueprint $table) {
            $table->id();
            $table->string('fasilitas')->nullable();
            $table->text('kerusakan')->nullable();
            $table->string('timeline')->nullable();
            $table->string('progress')->nullable();
            $table->text('rencana')->nullable();
            $table->bigInteger('budget')->nullable();
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
        Schema::dropIfExists('monitoring_perbaikans');
    }
}
