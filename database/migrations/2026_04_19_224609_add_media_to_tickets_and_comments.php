<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database/migrations/xxxx_xx_xx_xxxxxx_add_media_to_tickets_and_comments.php
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('media_path')->nullable()->after('description');
        });

        Schema::table('ticket_comments', function (Blueprint $table) {
            $table->string('media_path')->nullable()->after('comment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets_and_comments', function (Blueprint $table) {
            //
        });
    }
};
