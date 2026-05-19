<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameInventarisColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventaris_kantors', function (Blueprint $table) {
            if (Schema::hasColumn('inventaris_kantors', 'ceklist_perbaikan')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE inventaris_kantors CHANGE ceklist_perbaikan jumlah VARCHAR(255) NULL");
            }
            if (Schema::hasColumn('inventaris_kantors', 'tanggal_perbaikan')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE inventaris_kantors CHANGE tanggal_perbaikan tanggal_pembelian DATE NULL");
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventaris_kantors', function (Blueprint $table) {
            if (Schema::hasColumn('inventaris_kantors', 'jumlah')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE inventaris_kantors CHANGE jumlah ceklist_perbaikan TINYINT(1) DEFAULT 0 NOT NULL");
            }
            if (Schema::hasColumn('inventaris_kantors', 'tanggal_pembelian')) {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE inventaris_kantors CHANGE tanggal_pembelian tanggal_perbaikan DATE NULL");
            }
        });
    }
}
