<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarisKantorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventaris_kantors', function (Blueprint $table) {
            $table->id();
            $table->string('lokasi')->nullable();
            $table->string('nama_peralatan')->nullable();
            $table->string('status')->default('Normal');
            $table->text('keterangan')->nullable();
            $table->boolean('ceklist_perbaikan')->default(false);
            $table->date('tanggal_perbaikan')->nullable();
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
        Schema::dropIfExists('inventaris_kantors');
    }
}
