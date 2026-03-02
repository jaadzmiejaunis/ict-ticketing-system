<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Make sure to add this!

return new class extends Migration
{
    public function up(): void
    {
        // This changes the column from a strict ENUM to a flexible VARCHAR (string)
        DB::statement("ALTER TABLE tickets MODIFY status VARCHAR(255) DEFAULT 'Open'");
    }

    public function down(): void
    {
        // Revert it back if needed
        DB::statement("ALTER TABLE tickets MODIFY status ENUM('Open', 'Resolved') DEFAULT 'Open'");
    }
};
