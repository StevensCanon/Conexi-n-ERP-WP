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
        Schema::table('erps', function (Blueprint $table) {
            $table->string('base_uri')->after('nombre_erp')->nullable();
            $table->string('email')->after('base_uri')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('erps', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('base_uri');
        });
    }
};
