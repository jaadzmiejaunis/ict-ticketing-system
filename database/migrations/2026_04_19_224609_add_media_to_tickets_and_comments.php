<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('media_path')->nullable()->after('description');
        });

        Schema::table('ticket_comments', function (Blueprint $table) {
            $table->string('media_path')->nullable()->after('comment');
        });
    }

    public function down(): void
    {
        // Fixed: Drop the columns from the correct tables
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('media_path');
        });

        Schema::table('ticket_comments', function (Blueprint $table) {
            $table->dropColumn('media_path');
        });
    }
};
