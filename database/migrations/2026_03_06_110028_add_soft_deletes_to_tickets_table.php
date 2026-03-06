<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // This adds the 'deleted_at' column
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // This removes it if you rollback
            $table->dropSoftDeletes();
        });
    }
};
