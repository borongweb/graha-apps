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
        Schema::table('inventories', function (Blueprint $table) {
            $table->decimal('qty_out', 10, 2)->default(0);
            $table->decimal('qty_in', 10, 2)->default(0);
            $table->dropColumn('qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('qty_out');
            $table->dropColumn('qty_in');
            $table->string('qty');
        });
    }
};