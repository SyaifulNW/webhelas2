<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('salesplans', function (Blueprint $table) {
            for ($i = 1; $i <= 10; $i++) {
                $table->boolean("fu{$i}_wa")->default(false)->after("fu{$i}_tindak_lanjut");
                $table->boolean("fu{$i}_telp")->default(false)->after("fu{$i}_wa");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salesplans', function (Blueprint $table) {
            for ($i = 1; $i <= 10; $i++) {
                $table->dropColumn(["fu{$i}_wa", "fu{$i}_telp"]);
            }
        });
    }
};
