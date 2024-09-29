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
        Schema::table('inventory_outs', function (Blueprint $table) {
            $table->foreignId('inventories_id')->constrained()->onDelete('cascade')->after('time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_outs', function (Blueprint $table) {
            $table->dropForeign(['inventories_id']);
            $table->dropColumn('inventories_id');
        });
    }
};