<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoomSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zoom_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_id')->nullable();
            $table->unsignedBigInteger('salesplan_id')->nullable();
            $table->unsignedBigInteger('cs_id')->nullable();
            $table->dateTime('scheduled_at');
            $table->string('zoom_link')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, done, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();

            // Setup foreign keys or indexes if tables exist
            $table->index('data_id');
            $table->index('salesplan_id');
            $table->index('cs_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zoom_schedules');
    }
}
